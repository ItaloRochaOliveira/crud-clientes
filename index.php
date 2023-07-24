<?php 

require("lib/connection.php");


if(isset($_POST["email"]) && isset($_POST["senha"])){
    $email = $mysqli->escape_string($_POST["email"]);
    $senha = $_POST["senha"];

    $user_query = $mysqli->query("SELECT * FROM clientes WHERE email = '$email'") or die($mysqli->error);

   $error = false;
    
   if($user_query->num_rows == 0){
    echo "Email não encontrado.";
   }else{
    $user = $user_query->fetch_assoc();

    if(!isset($_SESSION))
            session_start();

    var_dump($_SESSION);

    if(!password_verify($senha, $user["senha"])){
        echo "Email ou senha invalidos.";
    } else {
        if(!isset($_SESSION))
            session_start();

        var_dump($_SESSION);

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
</head>

<body>
    <h1>Login</h1>
    <form action="" method="post">
        <p>
            <label for="email">
                Email:
            </label>
            <input type="text" name="email">
        </p>

        <p>
            <label for="senha">
                Senha:
            </label>
            <input type="text" name="senha">
        </p>

        <button type="submit">Entrar</button>
    </form>
</body>

</html>