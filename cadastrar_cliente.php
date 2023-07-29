<?php 
$erro = false;
$erro_css = false;


$referer = !empty($_SERVER['HTTP_REFERER']) ? pathinfo($_SERVER['HTTP_REFERER'], PATHINFO_BASENAME) : null;


if(count($_POST) > 0){
    var_dump($_POST);
    var_dump($_FILES);
    
    
    require("lib/connection.php");
    require("lib/upload.php");

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $nascimento = $_POST["nascimento"];
    $telefone = $_POST["telefone"];

    
    if(strlen($_POST["senha"]) < 6 && strlen($_POST["senha"]) < 16){
        $erro = "A senha deve ter entre 6 e 16 caracteres."; 
        $erro_css = "in_password";
    }

    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);


    if(empty($nome)){
        $erro = "Nome não informado, por favor, preecha o item nome.";
        $erro_css = "in-name";
    }

    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erro = "Email não informado, por favor, preecha o item email.";
        $erro_css = "in-email";
    }

    if (!empty($nascimento)){
        $tmp = explode("/", $nascimento);

        if(count($tmp) == 3){
            $nascimento = implode("-", array_reverse($tmp));

            echo $nascimento;
        } else {
            $erro = "A data deve seguir o padrão dia/mês/ano";
        }
    } 

    if(!empty($telefone)){
        $telefone = preg_replace("/[^0-9]/", "", $telefone); 
        
        if(strlen($telefone) != 11)
            $erro = "O telefone deve ser preechido no padrão (11) 4002-8922";

        echo $telefone;
    }

    $path = "";
    $file = $_FILES['foto'];

    
    if(!empty($file['name'])){
        $arq = $_FILES['foto'];
        $path = enviar_arquivo($arq["error"], $arq["size"], $arq['name'], $arq["tmp_name"]);
        if($path == false)
            $erro = "Falha ao enviar arquivo. Tente novamente.";
    }

    if(!$erro){
        $sql_code = "INSERT INTO clientes (nome, email, telefone, nascimento, data_de_cadastro, senha, foto) VALUES ('$nome', '$email','$telefone', '$nascimento', NOW(), '$senha', '$path')";

        $worked = $mysqli->query($sql_code) or die($mysqli->error);

        if($worked){
            echo "<p> <b> Cliente cadastrado com sucesso!!</p> </b>";
            unset($_POST);

            $sql_search = "SELECT id, admin FROM clientes WHERE email = '$email'";

            $search_successfully = $mysqli->query($sql_search) or die("Usuario não foi cadastrado");

            if($search_successfully->num_rows != 0){
                $user = $search_successfully->fetch_assoc();

                if(!isset($_SESSION))
                    session_start();

                $_SESSION["user"] = $user["id"];
                $_SESSION["admin"] = $user["admin"];

                header("Location: clientes.php");
            }

            
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
    <link rel="stylesheet" href="css/cadastrar_clientes.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <script type="text/javascript" src="js/showPassword.js" defer></script>

</head>

<body>
    <main>
        <section>
            <?php if(isset($referer) && $referer == "clientes.php"){?>
            <div>
                <a id="link" href="clientes.php"><button id="voltar">
                        &#60; Ir para a lista de cliente
                    </button> </a>
            </div>
            <?php }?>
            <div id="container-principal">
                <div id="cotainer-do-formulario">

                    <form action="" method="POST" id="form" enctype="multipart/form-data">
                        <h1 id="titulo">
                            <?php echo isset($referer) && $referer == "clientes.php" ? "Criar usuário" : "Criar cadastro"?>
                        </h1>

                        <p>
                            <label class="label" for="">Nome</label>

                        <div class="incluir-asterisco">
                            <input class="input" value="<?php if(isset($_POST['nome'])) echo $_POST['nome']?>"
                                name="nome" type="text">
                            <span
                                class="incluir-asterisco-2 <?php if($erro_css == "in-name"){echo "error-red";}?>">*</span>
                        </div>
                        </p>

                        <p>
                            <label class="label" for="">Email</label>

                        <div class="incluir-asterisco">
                            <input class="input" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>"
                                name="email" type="text">

                            <span
                                class="incluir-asterisco-2 <?php if($erro_css == "in-email"){echo "error-red";}?>">*</span>
                        </div>
                        </p>

                        <p>
                        <div>
                            <label class="label" for="">Data de Nascimento</label>
                            <input class="input"
                                value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento'] ?>"
                                name="nascimento" type="text">
                        </div>
                        </p>

                        <p>
                            <label for="">Telefone</label>
                            <input class="input" value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone'] ?>"
                                name="telefone" type="text" placeholder="(11) 4002-8922">
                        </p>

                        <p>
                            <label class="label" for="">Senha:</label>


                        <div id="password">
                            <div class="incluir-asterisco">
                                <input class="input" id="senha"
                                    value="<?php if(isset($_POST['senha'])) echo $_POST['senha'] ?>" name="senha"
                                    type="password">

                                <span
                                    class="incluir-asterisco-2 <?php if($erro_css == "in_password"){echo "error-red";}?>">*</span>
                            </div>

                            <span class="material-symbols-outlined">
                                visibility
                            </span>
                        </div>
                        </p>

                        <p>
                            <label class="label" id="send-photo" for="foto">Foto do usuário</label>

                            <input type="file" name="foto" id="escolher-ficheiro">
                        </p>

                        <?php if($erro){?>
                        <p id="error-message"><?php echo $erro . "*"?></p>
                        <?php } else{?>
                        <p id="error-message">itens obrigatórios!*</p>
                        <?php }?>

                        <div id="container-signup">
                            <button type="submit"
                                id="signup-botao"><?php echo isset($referer) && $referer == "clientes.php" ? "Criar usuário" : "Cadastrar"?></button>

                            <?php if(isset($referer) && $referer == "index.php"){?>
                            <a href="index.php" id="link-signup">já é usuário?</a>
                            <?php } ?>
                        </div>



                    </form>
                </div>
            </div>
        </section>
    </main>

</body>

</html>