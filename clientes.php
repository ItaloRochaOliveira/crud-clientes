<?php 
    
    if(!isset($_SESSION))
        session_start();

    if(!isset($_SESSION["user"])){
        header("Location: index.php");

        die();
    }

    include("lib/connection.php");

    $id = $_SESSION["user"];

    $sql_search_clientes =  "SELECT * FROM clientes" ;

    $query_clientes = $mysqli->query($sql_search_clientes) or die($mysqli->error);
    
    $num_clientes = $query_clientes->num_rows;
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="css/default.css">
    <link rel="stylesheet" href="css/clientes.css">

</head>

<body>

    <header>
        <button class="header-button"><a href="logout.php" class="link">&#60; Logout</a></button>

        <?php if($_SESSION["admin"]){?>
        <button class="header-button"><a href="cadastrar_cliente.php" class="link"> Ir para o
                cadastro &#62;</a></button>
        <?php }?>
    </header>
    <section>
        <main>
            <h1>Lista de Clientes</h1>
            <p>Estes são os clientes cadastrados no seu sistema: </p>

            <div id="cadastro">
                <table>
                    <thead>
                        <th>ID</th>
                        <th>ADM?</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Nascimento</th>
                        <th>Data</th>
                        <?php if($_SESSION["admin"]){?>
                        <th>Ações</th>
                        <?php }?>
                    </thead>
                    <tbody>
                        <?php 
            if($num_clientes == 0 ){ 
        ?>
                        <tr>
                            <td colspan="<?php echo !$_SESSION["admin"] ? 9 : 8 ?>"> Nenhum cliente foi cadastrado.</td>
                        </tr>
                        <?php } else {
            foreach($query_clientes as $cliente){

                $telefone = "Não informado";

                if(!empty($cliente["telefone"])){
                    $telefone = formatar_telefone($cliente["telefone"]);
                }

                $nascimento = "Não informado";

                if(!empty($cliente["nascimento"])){
                    $nascimento = formatar_data($cliente["nascimento"]);
                }

                $data_de_cadastro = date("d/m/Y H:i", strtotime($cliente['data_de_cadastro']))
        ?>
                        <tr>
                            <td><?php echo $cliente['id']?></td>
                            <td><?php echo $cliente['admin'] === "1" ? "sim" : "não"?></td>
                            <td id="image"><img src="<?php echo isset($cliente['foto'])  ? $cliente['foto'] : "arquivos/perfil.jpg" ?>
" alt="foto de perfil" id="foto">
                            </td>
                            <td><?php echo $cliente['nome']?></td>
                            <td><?php echo $cliente['email']?></td>
                            <td><?php echo $telefone?></td>
                            <td><?php echo $nascimento?></td>
                            <td><?php echo $data_de_cadastro?></td>
                            <td>
                                <a href="editar_cliente.php?id=<?php echo $cliente['id'] ?>"><button
                                        class="button-action edit-button">editar</button></a>
                                <?php if($_SESSION["admin"] && $cliente['id'] != $id){?>

                                <a href="deletar_cliente.php?id=<?php echo $cliente['id'] ?>"><button
                                        class="button-action delete-button">deletar</button></a>

                                <?php }  ?>
                            </td>

                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </main>


    </section>

</body>

</html>