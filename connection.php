<?php 

$hostname = "localhost";
$bancodedados = "crud_de_clientes";
$usuario = "root";
$senha = "";


$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

if($mysqli->connect_errno){
    echo "Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} 

function formatar_data($data){
    return implode("/", array_reverse(explode("-", $data)));
}

function formatar_telefone($telefone){
    $ddd = substr($telefone, 0, 2);
    $numero1 = substr($telefone, 2, 5);
    $numero2 = substr($telefone, 7);

    return $telefone = "($ddd) $numero1-$numero2";
}