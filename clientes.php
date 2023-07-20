<?php 
    include("lib/connection.php");

    $sql_search_clientes = "SELECT * FROM clientes";

    $query_clientes = $mysqli->query($sql_search_clientes) or die($mysqli->error);
    
    $num_clientes = $query_clientes->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <style>
    #botao {
        border-radius: 25px;
        min-height: 35px;
    }

    #link {
        text-decoration: none;
        color: black;
    }

    #cadastro {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    section {
        margin: 30px;
    }
    </style>
</head>

<body>
    <section>
        <main>
            <button id="botao"><a href="cadastrar_cliente.php" id="link">&#60; Voltar ao
                    cadastro</a></button>

            <div id="cadastro">
                <h1>Lista de Clientes</h1>
                <p>Estes são os clientes cadastrados no seu sistema: </p>

                <table border="1" cellpadding="10">
                    <thead>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Nascimento</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </thead>
                    <tbody>
                        <?php 
            if($num_clientes == 0 ){ 
        ?>
                        <tr>
                            <td colspan="7"> Nenhum cliente foi cadastrado.</td>
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
                            <td><?php echo $cliente['nome']?></td>
                            <td><?php echo $cliente['email']?></td>
                            <td><?php echo $telefone?></td>
                            <td><?php echo $nascimento?></td>
                            <td><?php echo $data_de_cadastro?></td>
                            <td>
                                <a href="editar_cliente.php?id=<?php echo $cliente['id'] ?>">editar</a>
                                <a href="deletar_cliente.php?id=<?php echo $cliente['id'] ?>">deletar</a>
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