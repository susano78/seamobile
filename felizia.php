<?php

session_start();
include('mysql.php');
include('global_controlador.php');
include('global_vista.php');
include('global_modelo.php');
include('afiliados_modelo.php');
include('afiliados_solo_clientes_modelo.php');
include('afiliados_modelo_8x4_modelo.php');
include('empresa_modelo.php');
include('iatematicas_modelo.php');

if(!isset($p) || $p==''){
        $p='index';
        if(isset($_GET['p']) && trim($_GET['p'])!=''){
                $p=$_GET['p'];
        }
}

if($check_auth==1){
     include('login_auth.php');
}

conectar();
rute_get($p);
desconectar();


function rute_get($p){
        switch ($p){
          case 'cerrar_sesion':
                try{
                 include('seguridad_controlador.php');
                 cerrar_sesion();
                 show_login();
                 } 
                 catch (Exception $e) {
                    // show_pagina_error($e);
                 }
          break;  
          case 'dologin':
                try{
                 include('seguridad_controlador.php');
                 do_login();
                 } 
                 catch (Exception $e) {
                    // show_pagina_error($e);
                 }
           break;
           case 'login':
                try{
                 include('seguridad_controlador.php');
                 show_login();
                 } 
                 catch (Exception $e) {
                    // show_pagina_error($e);
                 }
            break;
            case 'index':
                try{
                 include('index_controlador.php');
                 show_inicio();
                 } 
                 catch (Exception $e) {
                    // show_pagina_error($e);
                 }
            break; 
            default:
                //show_pagina_parametro_no_controlado();//en global_vista.php
            break;
        }
}













?>