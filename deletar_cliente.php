<?php 

if(!isset($_SESSION))
    session_start();

if( !$_SESSION["admin"]){
    header("Location: clientes.php");

    die();
}


include("lib/connection.php");

if(isset($_POST["confirm"])){
    echo "<p> funciona </p>";

    header("refresh:3; url=clientes.php");
?>

<head>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/delete_cliente.css">
</head>

<section id="exclusao-section">
    <p><b>Cliente excluido com sucesso!</b></p>
    <p>Redirecionando para a página de clientes...</p>
</section>

<?php
$id = intval($_GET['id']);

$sql_search_client = "SELECT foto FROM clientes WHERE id = '$id'";
$query_cliente = $mysqli->query($sql_search_client) or die($mysqli->error);

$cliente_foto = mysqli_fetch_assoc($query_cliente);

echo $cliente_foto["foto"];

if (!empty($cliente_foto["foto"])){
    unlink($cliente_foto["foto"]);
}


$sql_delete_code = "DELETE FROM clientes WHERE id = '$id'";
$sql_query = $mysqli->query($sql_delete_code) or die($mysqli->error);


die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/delete_cliente.css">
</head>

<body>
    <main>
        <section>
            <div>
                <h1>Tem certeza que deseja deletar este cliente?</h1>
                <div>
                    <a href="clientes.php"><button>Não</button></a>
                    <form action="" method="POST">
                        <button type="submit" name="confirm" value="1">Sim</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>

</html>