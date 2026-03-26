<?php

function get_idafiliado_login(){
    return $_SESSION["idafiliado_login"];
}

function esAfiliadoLoginSoloCliente(){
    return  false; //$_SESSION["aut"] == 'cliente';
}

function get_idempresa_login(){
    return $_SESSION["idempresa_login"];
}

function hasdato($valor){
    if(trim($valor)==''){
        return false;
    }
    else{
        return true;  
    }
}

function urlContieneTexto(string $texto)
{
    // Construye la URL actual
    $urlActual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
    $urlActual .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // Verifica si contiene el texto
    return strpos($urlActual, $texto) !== false;
}

function esDesarrollo()
{
 return urlContieneTexto('desarrollo');
}

function getCarpetaRoot()
{
 if (esDesarrollo()) return 'desarrollo/';
 return '';
}


 ?>
