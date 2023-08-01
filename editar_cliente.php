<?php 

if(!isset($_SESSION))
    session_start();

if (!$_SESSION["admin"]){
    header("Location: clientes.php");

    die();
}


include("lib/connection.php");
require("lib/upload.php");

$id = intval($_GET['id']);
$sql_search_client = "SELECT * FROM clientes WHERE id = '$id'";
$query_cliente = $mysqli->query($sql_search_client) or die($mysqli->error);

$cliente = mysqli_fetch_assoc($query_cliente);

$erro = false;
$message = false;

// function change_message($value) {
//     global $message;
//     if($value){
      
//     } else {
//       echo "entrou 2";


//     sleep(2);

//     echo $message;

//     $message = $value;
//     }

// }


if(count($_POST) > 0){
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $nascimento = $_POST["nascimento"];
    $telefone = $_POST["telefone"];
    $nova_senha = $_POST["senha"];

   
    $sql_extra = "";
 


    if(!empty($nova_senha)){
        if(strlen($nova_senha) < 6 && strlen($nova_senha) < 16)
            $erro = "A senha deve ter de 6 a 16 caracteres.";
        else {
            $senha_criptografada = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql_extra = " senha = '$senha_criptografada', ";
        }
    }

    $path = null;
    $file = $_FILES['foto'];

    if(!empty($file['name'])){
        var_dump($_FILES);
        
        $arq = $_FILES['foto'];
        $path = enviar_arquivo($arq["error"], $arq["size"], $arq['name'], $arq["tmp_name"]);
        if($path == false)
            $erro = "Falha ao enviar arquivo. Tente novamente.";
        else 
            $sql_extra .= " foto = '$path', ";

        if($cliente["foto"]){
            unlink($cliente["foto"]);
        }
    }
    

    if(empty($nome)){
        $erro = "Nome não informado, por favor, preecha o item nome.";
    }

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erro = "Email não informado, por favor, preecha o item email.";
    }

    if (!empty($nascimento)){
        $tmp = explode("/", $nascimento);

        if(count($tmp) == 3){

            $nascimento = implode("-", array_reverse($tmp));
        } else {
            $erro = "A data deve seguir o padrão dia/mês/ano";
        }
    } 

    if(!empty($telefone)){
        $telefone = preg_replace("/[^0-9]/", "", $telefone); 
        
        if(strlen($telefone) != 11)
            $erro = "O telefone deve ser preechido no padrão (11) 4002-8922";
    }

    if(!$erro){
        
        $sql_code = "UPDATE clientes
        SET nome = '$nome',
        email = '$email',
        telefone = '$telefone',
        $sql_extra
        nascimento = '$nascimento'
        
        WHERE id = '$id' ";

        $worked = $mysqli->query($sql_code) or die($mysqli->error);

        if($worked){
            $message ="<p> <b> Cliente atualizado com sucesso!!</p> </b>";
            unset($_POST);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/editar_cliente.css">
    <script type="text/javascript" src="js/changeMessageUpdate.js" defer></script>
</head>

<body>
    <main>
        <section>
            <div>
                <button id="voltar">
                    <a id="link" href="clientes.php">&#60; Voltar para a lista de cliente</a>
                </button>
            </div>

            <div id="container-principal">
                <div id="cotainer-do-formulario">

                    <form action="" method="POST" id="form" enctype="multipart/form-data">
                        <h1 id="titulo"> Editar cliente o <?php echo $cliente["nome"]?>:
                        </h1>
                        <p>
                            <label class="label" for="">Nome</label>
                            <input class="input" value="<?php echo $cliente['nome']?>" name="nome" type="text">
                        </p>
                        <p>
                            <label class="label" for="">Email</label>
                            <input class="input" value="<?php echo $cliente['email'] ?>" name="email" type="text">
                        </p>

                        <p>
                            <label class="label" for="">Data de Nascimento</label>
                            <input class="input"
                                value="<?php if(!empty($cliente['nascimento'])) echo formatar_data($cliente['nascimento']) ?>"
                                name="nascimento" type="text">
                        </p>

                        <p>
                            <label class="label" for="">Telefone</label>
                            <input class="input"
                                value="<?php if(!empty($cliente['telefone'])) echo formatar_telefone($cliente['telefone']) ?>"
                                name="telefone" type="text" placeholder="(11) 4002-8922">
                        </p>

                        <p>
                            <label for="">Nova senha</label>
                            <input class="input" name="senha" type="text">
                        </p>


                        <p id="container-img">
                            <img src="<?php echo isset($cliente['foto']) ? $cliente['foto'] : "arquivos/perfil.jpg" ?>
" alt="foto de perfil" id="foto">

                            <span>
                                <label for="">Foto do usuário</label>
                                <input type="file" name="foto" id="escolher-ficheiro">
                            </span>
                        </p>

                        <p>
                            <button type="submit" id="button">Salvar cliente</button>
                        </p>

                    </form>

                    <div id="to-hide-message">
                        <?php echo $message ;
                    
                    sleep(2);
                    ?>

                    </div>
                </div>
            </div>




        </section>


    </main>



</body>

</html>