<?php 

include("lib/connection.php");

if(isset($_POST["confirm"])){
    echo "<p> funciona </p>";

    header("refresh:3; url=clientes.php");
?>

<head>
    <style>
    #exclusao-section {
        position: static;
        top: 50%;
        left: 50%;

        /* transform: translate(-50%, -50%); */


        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        width: 30%;
        margin: auto;

        border: 1px solid black;
    }
    </style>
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
    <style>
    section {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        margin: 30px;
    }

    section>div:first-child {
        width: 200px;

        align-items: center;
        padding: 0px 20px;
        min-width: 200px;
        width: 30%;
        padding: 10px;

        border: 1px solid black;
        border-radius: 25px;
    }

    section div div {
        display: flex;
        justify-content: center;
        gap: 80px;
    }

    section button {
        border-radius: 25px;
        width: 100px;
        height: 40px;
    }

    #exclusao-section {
        position: static;
        top: 50%;
        left: 50%;

        /* transform: translate(-50%, -50%); */


        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        width: 30%;
        margin: auto;

        border: 1px solid black;
    }
    </style>
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