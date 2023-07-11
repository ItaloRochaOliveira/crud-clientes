<?php 

include("connection.php");

$id = intval($_GET['id']);
$sql_search_client = "SELECT * FROM clientes WHERE id = '$id'";
$query_cliente = $mysqli->query($sql_search_client) or die($mysqli->error);

$cliente = mysqli_fetch_assoc($query_cliente);

$erro = false;

if(count($_POST) > 0){
    

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $nascimento = $_POST["nascimento"];
    $telefone = $_POST["telefone"];
    

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

    if($erro){
        echo "<p> <b>ERRO: $erro</p> </b>";
    } else {
        $sql_code = "UPDATE clientes
        SET nome = '$nome',
        email = '$email',
        telefone = '$telefone',
        nascimento = '$nascimento'
        WHERE id = '$id'";

        $worked = $mysqli->query($sql_code) or die($mysqli->error);

        if($worked){
            echo "<p> <b> Cliente atualizado com sucesso!!</p> </b>";
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
    <style>
    #voltar {
        border-radius: 25px;
        min-height: 35px;
    }

    #link {
        text-decoration: none;
        color: black;
    }

    section {
        margin: 30px;
    }

    #container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #form {
        /* display: flex;
        flex-direction: column;
        justify-content: center; */
        align-items: center;
        padding: 0px 20px;
        min-width: 200px;
        width: 30%;

        border: 1px solid black;
        border-radius: 25px;
    }

    #button {
        display: flex;
        justify-content: center;
        margin-top: 40px;

        border-radius: 25%;
        min-height: 35px;
    }
    </style>
</head>

<body>
    <main>
        <section>
            <div>
                <button id="voltar">
                    <a id="link" href="clientes.php">&#60; Voltar para a lista de cliente</a>
                </button>
            </div>
            <div id="container">
                Editar cliente <?php echo $cliente["nome"]?>:
                <form action="" method="POST" id="form">
                    <p>
                        <label for="">Nome:</label>
                        <input value="<?php echo $cliente['nome']?>" name="nome" type="text">
                    </p>

                    <p>
                        <label for="">Email:</label>
                        <input value="<?php echo $cliente['email'] ?>" name="email" type="text">
                    </p>

                    <p>
                        <label for="">Data de Nascimento:</label>
                        <input
                            value="<?php if(!empty($cliente['nascimento'])) echo formatar_data($cliente['nascimento']) ?>"
                            name="nascimento" type="text">
                    </p>

                    <p>
                        <label for="">Telefone:</label>
                        <input
                            value="<?php if(!empty($cliente['telefone'])) echo formatar_telefone($cliente['telefone']) ?>"
                            name="telefone" type="text" placeholder="(11) 4002-8922">
                    </p>

                    <p id="button">
                        <button type="submit">Salvar cliente</button>
                    </p>

                </form>
            </div>
        </section>
    </main>

</body>

</html>