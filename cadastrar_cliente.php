<?php 

if(!isset($_SESSION))
    session_start();

if(!$_SESSION["admin"]){
    header("Location: clientes.php");

    die();
}

$erro = false;

if(count($_POST) > 0){
    require("lib/connection.php");
    require("lib/upload.php");

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $nascimento = $_POST["nascimento"];
    $telefone = $_POST["telefone"];
    
    if(strlen($_POST["senha"]) < 6 && strlen($_POST["senha"]) < 16){
        $erro = "A senha deve ter entre 6 e 16 caracteres."; 
    }

    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

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
    if(isset($_FILES['foto'])){
        $arq = $_FILES['foto'];
        $path = enviar_arquivo($arq["error"], $arq["size"], $arq['name'], $arq["tmp_name"]);
        if($path == false)
            $erro = "Falha ao enviar arquivo. Tente novamente.";
    }

    if($erro){
        echo "<p> <b>ERRO: $erro</p> </b>";
    } else {
        $sql_code = "INSERT INTO clientes (nome, email, telefone, nascimento, data_de_cadastro, senha, foto) VALUES ('$nome', '$email','$telefone', '$nascimento', NOW(), '$senha', '$path')";

        $worked = $mysqli->query($sql_code) or die($mysqli->error);

        if($worked){
            echo "<p> <b> Cliente cadastrado com sucesso!!</p> </b>";
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
    section>div:first-child {
        display: flex;
        justify-content: right;
    }

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
                    <a id="link" href="clientes.php">Ir para a lista de cliente &#62;</a>
                </button>
            </div>
            <div id="container">
                Cadastrar cliente:
                <form action="" method="POST" id="form" enctype="multipart/form-data">
                    <p>
                        <label for="">Nome:</label>
                        <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']?>" name="nome" type="text">
                    </p>

                    <p>
                        <label for="">Email:</label>
                        <input value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" name="email"
                            type="text">
                    </p>

                    <p>
                        <label for="">Data de Nascimento:</label>
                        <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento'] ?>"
                            name="nascimento" type="text">
                    </p>

                    <p>
                        <label for="">Telefone:</label>
                        <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone'] ?>" name="telefone"
                            type="text" placeholder="(11) 4002-8922">
                    </p>

                    <p>
                        <label for="">Senha:</label>
                        <input value="<?php if(isset($_POST['senha'])) echo $_POST['senha'] ?>" name="senha"
                            type="text">
                    </p>

                    <p>
                        <label for="">Foto do usuário:</label>
                        <input type="file" name="foto">
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