<?php 

require("lib/connection.php");

if(isset($_POST["email"]) && isset($_POST["senha"])){
    $email = $mysqli->escape_string($_POST["email"]);
    $senha = $_POST["senha"];

    $user_query = $mysqli->query("SELECT * FROM clientes WHERE email = '$email'") or die($mysqli->error);

   $error = false;

   $error_mensage = false;
    
   if($user_query->num_rows == 0){
    $error_mensage = "Email não encontrado ou inexistente.";
   }else{
    $user = $user_query->fetch_assoc();

    if(!isset($_SESSION))
            session_start();

    if(!password_verify($senha, $user["senha"])){
        $error_mensage = "Email ou senha invalidos.";
    } else {
        if(!isset($_SESSION))
            session_start();

        $_SESSION["user"] = $user["id"];
        $_SESSION["admin"] = $user["admin"];

        header("Location: clientes.php");
    }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/index.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <script type="text/javascript" src="js/showPassword.js" defer></script>
</head>

<body>
    <div id="container-principal">
        <div id="cotainer-do-formulario">
            <h1 id="titulo">Login</h1>
            <form action="" method="post">
                <p>
                    <label for="email" class="label">
                        Email
                    </label>
                    <br>
                    <input type="text" name="email" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>"
                        class="input">
                </p>

                <p>
                    <label for="senha" class="label">
                        Senha
                    </label>

                    <br>

                <div id="password">
                    <input type="password" name="senha" class="input" id="senha">

                    <span class="material-symbols-outlined">
                        visibility
                    </span>

                </div>
                </p>

                <?php if(isset($error_mensage)){?>
                <p id="error-message"><?php echo $error_mensage . "*"?></p>
                <?php }?>

                <div id="container-login">
                    <button type="submit" id="login-botao">Entrar</button>

                    <a href="cadastrar_cliente.php" id="link-signup">ainda não sou cliente</a>
                </div>

                <div>

                </div>
            </form>
        </div>
    </div>


</body>

</html>