<?php

include('seguridad_modelo.php');

function show_login(){
    $arvista=array();
    
    $empresa_get='';
    if(isset($_GET["empresa"])){
       if(trim($_GET["empresa"])!=''){
           $empresa_get=trim($_GET["empresa"]);   
       }
    }
    $arvista['empresa_get']=$empresa_get; 
    include('login_vista.php');
    
}

function cerrar_sesion(){
    session_destroy();
}

function do_login(){
    $user=$_POST['username'];
    $pass=$_POST['password'];
    $empresa=$_POST['empresa'];
    
    //login si es un afiliado
    $result_afiliado=get_usuario_afiliado_login($user,$pass,$empresa); //se valida si se trata de un afiliado
    $n_result_afiliado=count($result_afiliado);
    if($n_result_afiliado==1){
        
            $row_afiliado=$result_afiliado[0];

            $_SESSION["autentificado"] = "SI";
            $_SESSION["usuario"] = ecd($row_afiliado['nombre']);
            $_SESSION["idafiliado_login"] = $row_afiliado['id_afiliado'];
            $_SESSION["aut"] = 'afiliado';  //perfil
            $_SESSION["empresa"] = $empresa;
            $_SESSION["idempresa_login"] = $row_afiliado['id_empresa'];
            $_SESSION["idcliente_login"] = $row_afiliado['cliente_id'];
            
            $rs_afiliado=get_afiliado_byId($row_afiliado['id_afiliado']);
            $rw_afiliado=$rs_afiliado[0];

            $saldo_tokens=obtenerSaldoTokensAfiliado(get_idafiliado_login());

            desconectar();

            $sw_ativo=sw_estado_afiliado_activo($rw_afiliado);

            if($sw_ativo && $saldo_tokens > 0){
                header(header: "Location: index.php");
            }else{
                session_destroy();
                $param_tk = $sw_ativo && $saldo_tokens < 1 ? "tk=1&": "";

                header("Location: registro/paypal_afiliados/index.php?".$param_tk."idafiliado=".$rw_afiliado['id_md5']);
            }  
            // header("Location: index.php");

    }else{

    //login si es un afiliado solo cliente
    $result_afiliado=get_usuario_afiliados_solo_clientes_login($user,$pass,$empresa); //se valida si se trata de un afiliado solo cliente
    $n_result_afiliado=count($result_afiliado);
    if($n_result_afiliado==1){
        
            $row_afiliado=$result_afiliado[0];

            $_SESSION["autentificado"] = "SI";
            $_SESSION["usuario"] = ecd($row_afiliado['nombre']);
            $_SESSION["idafiliado_login"] = $row_afiliado['id_afiliado'];
            $_SESSION["aut"] = 'cliente';  //perfil
            $_SESSION["empresa"] = $empresa;
            $_SESSION["idempresa_login"] = $row_afiliado['id_empresa'];
            $_SESSION["idcliente_login"] = $row_afiliado['cliente_id'];
            
            $rs_afiliado=get_afiliado_solo_cliente_byId($row_afiliado['id_afiliado']);
            $rw_afiliado=$rs_afiliado[0];

            $saldo_tokens=obtenerSaldoTokensAfiliado(get_idafiliado_login());

            desconectar();

            $sw_ativo=sw_estado_afiliado_activo($rw_afiliado);

            if($sw_ativo && $saldo_tokens > 0){
                header(header: "Location: index.php");
            }else{

                $param_tk = $sw_ativo && $saldo_tokens < 1 ? "tksc=1&": "";
                $param_sc = !$sw_ativo  ? "sc=1&": "";

                header("Location: registro/paypal_afiliados/index.php?".$param_tk.$param_sc."idafiliado=".$rw_afiliado['id_md5']);
            }  
    // //fin login si es un afiliado
    
    //     $result_clidecli=get_usuario_clidecli_login($user,$pass,$empresa); //se valida si se trata de un clidecli
    //     $n_result_clidecli=count($result_clidecli);
    //     if($n_result_clidecli==1){
        
    //         $row_clidecli=$result_clidecli[0];

    //         $_SESSION["autentificado"] = "SI";
    //         $_SESSION["usuario"] = ecd($row_clidecli[nombre]);
    //         $_SESSION["idclidecli_login"] = $row_clidecli[id_clidecli];
    //         $_SESSION["aut"] = 'clidecli';  //perfil
    //         $_SESSION["empresa"] = $empresa;


    //         desconectar();
    //         header("Location: index.php?p=proyectos&tp=".$row_clidecli[proyecto]."&op=ficha");

    }else
        
        //{

        // $result_cliente=get_usuario_cliente_login($user,$pass,$empresa); //se valida si se trata de un cliente
        // $n_result_cliente=count($result_cliente);
        // if($n_result_cliente==1){

        //     $row_cliente=$result_cliente[0];

        //     //session_start(); 
        //     $_SESSION["autentificado"] = "SI";
        //     $_SESSION["usuario"] = $row_cliente[usuario_cliente];
        //     $_SESSION["idcliente_login"] = $row_cliente[id_emp];
        //     $_SESSION["aut"] = 'cliente';  //perfil
        //     $_SESSION["empresa"] = $empresa;


        //     desconectar();
        //     header("Location: index.php");

        // }else
        
        { //si no existe como cliente, se valida para el resto de perfiles

		// $result=get_usuario_login($user,$pass,$empresa);
		// $n_result=count($result);
		// if($n_result==1){

		// 	$row=$result[0];
		// 	$aut=$row[categoria];
			
		// 	//session_start(); 
		// 	$_SESSION["autentificado"] = "SI";
		// 	$_SESSION["usuario"] = $user;
        //                 $_SESSION["idusuario_login"] = $row['id']; //para guardar los automailings
		// 	$_SESSION["aut"] = $aut;  //perfil
		// 	$_SESSION["empresa"] = $empresa;
        //                 $_SESSION["modulos_usuario"]=get_ar_modulos_by_usuario($_SESSION["usuario"]
        //                                                                       ,$_SESSION["empresa"]);
        //                 //embudos_abril2019
        //                 $rsEmpresa = get_empresa_byName($empresa);
        //                 $_SESSION["idempresa_login"] = $rsEmpresa[0]['id_e'];
        //                 //fin embudos_abril2019
                        
        //                 desconectar();
        //                 header("Location: index.php");
	
		// } else {

                    $arvista=array();
                    $empresa_get='';
                    if(isset($_GET["empresa"])){
                       if(trim($_GET["empresa"])!=''){
                           $empresa_get=trim($_GET["empresa"]);   
                       }
                    }
                    $arvista['empresa_get']=$empresa_get; 
                    include('login_vista.php');
		//}

      }
    }
}
// }
// }



