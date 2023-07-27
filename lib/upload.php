<?php

function enviar_arquivo($error, $size, $name, $tmp){

    if($error){
        die("Falha ao enviar arquivo a");
    }

    if($size > 2097152) //2 * 1024 * 1024
        die("Arquivo muito grande!! Mx: 2MB");

    $pasta = "arquivos/";
    $nome_do_arquivo = $name;
    $novo_nome_do_arquivo = uniqid();
    $extensao = strtolower(pathinfo($nome_do_arquivo, PATHINFO_EXTENSION));

    if($extensao != "jpg" && $extensao != "png")
        die("Tipo de arquivo não aceito");

    $path = $pasta . $novo_nome_do_arquivo . "." . $extensao;

    $deu_certo = move_uploaded_file($tmp, $path);

    if($deu_certo){
        return $path;
    } else {
        return false;
    }
}
?>