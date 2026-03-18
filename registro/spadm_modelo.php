<?php
	
function get_sql_empresas(){
    $sql="SELECT id_e idempresa
                ,empresa
                ,cta_paypal
             FROM empresas 
             ORDER BY empresa";
    return $sql;
}

function get_empresaById($id){
    $sql="SELECT id_e idempresa
                ,empresa
                ,cta_paypal
             FROM empresas
             WHERE id_e=".$id;
    
    return get_registros($sql);
}

function get_sql_maestro_modulos(){
    $sql="SELECT id idmodulo 
                ,nombre
                ,parametro
            FROM modulos
            ORDER BY nombre";
    return $sql;
}

function get_moduloById($id){
    $sql="SELECT id idmodulo 
                ,nombre
                ,parametro
            FROM modulos
            WHERE id=".$id;
    
    return get_registros($sql);
}


function get_modulos_empresa_chk($idempresa){
    $sql="select   
         modu.id moduloid
        ,modu.nombre modulo
        ,IF(IFNULL(emp.id_e,0) = 0, 0, 1) chk		
	 from modulos modu 
         left join modulos_empresa modem 
		   on modem.moduloid=modu.id 
		   and modem.empresaid=".$idempresa."
	 left join empresas emp 
		   on modem.empresaid=emp.id_e 	
         order by modu.nombre";
    
    return get_registros($sql);
}


function get_usuariosperfil_empresa_chk($empresa){
    $sql="SELECT us.id idusuario
                ,us.usuario
                ,us.pass
                ,us.categoria
                ,us.email
                ,STR_TO_DATE(us.fecha,'%d/%m/%Y') fecha
                ,IF(LOWER(IFNULL(us.acceso,'si')) = 'si', 1, 0) chk 
        FROM usuarios us
        where us.empresa='".$empresa."'
        order by us.usuario asc
                ,us.categoria asc";
    
    return get_registros($sql);
}

function get_all_categorias_usuarios(){
    $sql="SELECT DISTINCT categoria, categoria 
          from usuarios 
          order by categoria";
    
    return get_registros($sql);
}

function get_all_parametros_modulos(){
    $sql="SELECT DISTINCT parametro, parametro 
    from modulos 
    order by parametro";
    
    return get_registros($sql);
}

/* function get_usuariosperfil_empresa_chk($empresa){
    $sql="SELECT DISTINCT us.usuario
                         ,us.categoria
                         ,IF(IFNULL(usem.empresa,'0') = '0', 0, 1) chk 
        FROM usuarios us
        left join (select usuario,empresa from usuarios 
           where empresa='".$empresa."' and acceso='si') usem 
           on us.usuario=usem.usuario
        order by us.usuario asc
                ,us.categoria asc";
    
    return get_registros($sql);
} */


function set_procesar_checks_1Empresa_N_Usuarios_post(){
    $ar=array();
    $resultFinal=true;
    $anyset=false;
    foreach($_POST as $field=>$value)
    {
       //si el check es falso, no viaja en el post...que cabrón
       //por tanto se ponen los chks a falso en bbdd y después se procesan los checks
       if(startsWith($field, 'hidden_')){                                                  
                $ar=explode("_", $field);
                $param=array();
                $param['idusuario']=$ar[1];
                $param['acceso']= $value;
                $result=set_acceso_usuario_bbdd($param);
                if(!$result) $resultFinal=!$result; else $anyset=true;
        }
    }
    foreach($_POST as $field=>$value)
    {
        if(startsWith($field, 'chkactivo_')){ 
               $ar=explode("_", $field);
               $param=array();
               $param['idusuario']=$ar[1];
               $param['acceso']= strtolower($value)=='on' ? 'si':'no';
               $result=set_acceso_usuario_bbdd($param);
               if(!$result) $resultFinal=!$result; else $anyset=true;
        }
    }

    $ar['estado_escritura_bd']=($resultFinal && $anyset);
    return $ar;
}

function set_procesar_checks_1Empresa_N_Modulos_post(){
    $ar=array();
    $ar_procesar=array();
    $resultFinal=true;
    $idempresa=get_PostorGet('idempresa');
    foreach($_POST as $field=>$value)
    {
        if(startsWith($field, 'hidden_')){                                                  
              $ar=explode("_", $field);
              $moduloid=$ar[1];
              $ar_procesar[$moduloid] = 0;
         }
    }
    foreach($_POST as $field=>$value)
    {
         if(startsWith($field, 'chkactivo_')){ 
                $ar=explode("_", $field);
                $moduloid=$ar[1];
                $ar_procesar[$moduloid]= strtolower($value)=='on' ? 1:0;
         }
    }
      
    foreach($ar_procesar as $moduloid=>$value)
    {
        $paramSelect=array();
        $paramSelect['empresaid']=$idempresa;
        $paramSelect['moduloid']=$moduloid;
        $rs=get_rs_modulo_empresa_bd_byIds($paramSelect);
        $moduloempresaid = count($rs) > 0 ? $rs[0]['id']: 0;
        if($value==1){ //check activado: se inserta permiso si no existe
            if($moduloempresaid==0){
                $result=set_new_modulo_empresa_bbdd($paramSelect);
                if(!$result) $resultFinal=!$result;    
            }
        }else{
              if($moduloempresaid>0){ //check desactivado: se borra permiso si existe
                $paramDelete=array();
                $paramDelete['moduloempresaid']=$moduloempresaid;
                $result=set_delete_depedencia_modulos_usuarios_bbdd($paramDelete);
                if(!$result) $resultFinal=!$result;
                $result=set_delete_modulo_empresa_bbdd($paramDelete);  
                if(!$result) $resultFinal=!$result;  
              }    
        }
    }

    $ar['estado_escritura_bd']=$resultFinal;
    return $ar;   
}


function set_empresa_post(){    
    $isnew=$_POST['idempresa']=='new' ? true:false;
    $rtn=array();
    $param=array();

    $param['idempresa']=$_POST['idempresa'];
    $param['empresa']=utf8_decode($_POST['name_empresa']);  
    $param['cta_paypal']=$_POST['cta_paypal'];

    if($isnew){
        $new_idempresa=get_next_id_tabla('empresas');
    }
    
    $result=set_empresa_bbdd($param); 

    if($isnew){
        $param['idempresa']=$new_idempresa;
    }

    $rtn['estado_escritura_bd']=$result;
    $rtn['idempresa']=$param['idempresa'];
    
    return $rtn;
}

function set_empresa_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';

    $ar['id']=$param['idempresa'];
    $ar['s#empresa']=$param['empresa'];
    $ar['s#cta_paypal']=$param['cta_paypal'];

    $result=save_array_bd($ar); 
    return $result;
}



function set_acceso_usuario_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='usuarios'; 
    $ar['campopk']='id';

    $ar['id']=$param['idusuario'];
    $ar['s#acceso']=$param['acceso'];

    $result=save_array_bd($ar); 
    return $result;
}


function set_cliente_entidad_tipo_post(){
    $isnew=$_POST['idcliente_entidad_tipo']=='new' ? true:false;
    $rtn=array();
    $param=array();

    $param['idcliente_entidad_tipo']=$_POST['idcliente_entidad_tipo'];
    $param['nombre']=utf8_decode($_POST['cliente_entidad_tipo']);
    $param['parametro']=utf8_decode($_POST['parametro_cliente_entidad_tipo']);
    $param['entidadid']=hasdato($_POST['entidadid']) ? $_POST['entidadid'] : 'null';
    $param['empresaid']=get_PostorGet('id');  
    $param['moduloid']=hasdato($_POST['moduloid2']) ? $_POST['moduloid2'] : 'null';

    if($isnew){
        $new_idcliente_entidad_tipo=get_next_id_tabla('cliente_entidad_tipo');
    }
    
    $result=set_cliente_entidad_tipo_bbdd($param); 

    if($isnew){
        $param['idcliente_entidad_tipo']=$new_idcliente_entidad_tipo;
    }

    $rtn['estado_escritura_bd']=$result;
    $rtn['idcliente_entidad_tipo']=$param['idcliente_entidad_tipo'];
    
    return $rtn;

}

function set_cliente_entidad_tipo_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='cliente_entidad_tipo'; 
    $ar['campopk']='id';

    $ar['id']=$param['idcliente_entidad_tipo'];
    $ar['s#nombre']=$param['nombre'];
    $ar['s#parametro']=$param['parametro'];
    $ar['n#moduloid']=$param['moduloid'];
    $ar['n#entidadid']=$param['entidadid'];

    if($ar['id']=='new'){
        $ar['n#empresaid']=$param['empresaid'];
    }
  
    $result=save_array_bd($ar); 
    return $result;
}

function set_cliente_cambio_tipo_post(){
    $isnew=$_POST['idcliente_cambio_tipo']=='new' ? true:false;
    $rtn=array();
    $param=array();

    $param['idcliente_cambio_tipo']=$_POST['idcliente_cambio_tipo'];
    $param['tipo_origen']=utf8_decode($_POST['tipo_origen']);
    $param['tipo_destino']=utf8_decode($_POST['tipo_destino']);
    $param['identidad']=hasdato($_POST['entidadid2']) ? $_POST['entidadid2'] : 'null';
 
    $id_empresa=get_PostorGet('id');
    $rs_empresa=get_empresaById($id_empresa);
    $param['empresa']=$rs_empresa[0]['empresa']; 

    if($isnew){
        $new_idcliente_cambio_tipo=get_next_id_tabla('cliente_cambio_tipo');
    }
    
    $result=set_cliente_cambio_tipo_bbdd($param); 

    if($isnew){
        $param['idcliente_cambio_tipo']=$new_idcliente_cambio_tipo;
    }

    $rtn['estado_escritura_bd']=$result;
    $rtn['idcliente_cambio_tipo']=$param['idcliente_cambio_tipo'];
    
    return $rtn;

}


function set_cliente_cambio_tipo_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='cliente_cambio_tipo'; 
    $ar['campopk']='id';

    $ar['id']=$param['idcliente_cambio_tipo'];
    $ar['s#tipo_origen']=$param['tipo_origen'];
    $ar['s#tipo_destino']=$param['tipo_destino'];
    $ar['n#identidad']=$param['identidad'];

    if($ar['id']=='new'){
        $ar['s#empresa']=$param['empresa'];
    }
  
    $result=save_array_bd($ar); 
    return $result;
}



 function set_proyecto_post(){
    $isnew=$_POST['idproyecto']=='new' ? true:false;
    $rtn=array();
    $param=array();

     $param['idproyecto']=$_POST['idproyecto'];
     $param['proyecto']=utf8_decode($_POST['proyecto']);
     $param['perfil_cliente_registrar_clideclis']=
          $_POST['chkperfil_cliente_registrar_clideclis'] == 'on' ? 1:0;
     $param['moduloid']=hasdato($_POST['moduloid']) ? $_POST['moduloid'] : 'null';
  
     $id_empresa=get_PostorGet('id');
     $rs_empresa=get_empresaById($id_empresa);
     $param['empresa']=$rs_empresa[0]['empresa'];  


    if($isnew){
        $new_idproyecto=get_next_id_tabla('proyectos');
    }
    
    $result=set_proyecto_bbdd($param); 

    if($isnew){
        $param['idproyecto']=$new_idproyecto;
    }

     $rtn['estado_escritura_bd']=$result;
     $rtn['idproyecto']=$param['idproyecto'];
    
     return $rtn;

}

function set_proyecto_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='proyectos'; 
    $ar['campopk']='id_pro';

    $ar['id']=$param['idproyecto'];
    $ar['s#proyecto']=$param['proyecto'];
    $ar['n#perfil_cliente_registrar_clideclis']=$param['perfil_cliente_registrar_clideclis'];
    $ar['n#moduloid']=$param['moduloid'];

    if($ar['id']=='new'){
        $ar['s#empresa']=$param['empresa'];
        $ar['n#interno']=0;
    }
  
    $result=save_array_bd($ar); 
    return $result;
}

function set_usuario_post(){    
    $isnew=$_POST['idusuario']=='new' ? true:false;
    $rtn=array();
    $param=array();

     $param['idusuario']=$_POST['idusuario'];
     $param['usuario']=utf8_decode($_POST['usuario']);  
     $param['pass']=utf8_decode($_POST['contrasenia']);
     $param['email']=$_POST['email']; 
     $param['categoria']=utf8_decode($_POST['categoria']);

     $id_empresa=get_PostorGet('id');
     $rs_empresa=get_empresaById($id_empresa);
     $param['empresa']=$rs_empresa[0]['empresa'];  


    if($isnew){
        $new_idusuario=get_next_id_tabla('usuarios');
    }
    
    $result=set_usuario_bbdd($param); 

    if($isnew){
        $param['idusuario']=$new_idusuario;
    }

    $rtn['estado_escritura_bd']=$result;
    $rtn['idusuario']=$param['idusuario'];
    
    return $rtn;
}
function set_usuario_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='usuarios'; 
    $ar['campopk']='id';

    $ar['id']=$param['idusuario'];
    $ar['s#usuario']=$param['usuario'];
    $ar['s#pass']=$param['pass'];
    $ar['s#categoria']=$param['categoria'];
    $ar['s#email']=$param['email'];
    $ar['s#empresa']=$param['empresa'];
    

    if($ar['id']=='new'){
        $ar['s#fecha']=date("j/n/Y");
        $ar['s#acceso']='SI';
    }

    $result=save_array_bd($ar); 
    return $result;
}

function set_new_modulo_empresa_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='modulos_empresa'; 
    $ar['campopk']='id';

    $ar['id']='new';
    $ar['n#empresaid']=$param['empresaid'];
    $ar['n#moduloid']=$param['moduloid'];

    $result=save_array_bd($ar); 
    return $result;
}


function set_new_modulo_usuario_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='modulos_usuarios'; 
    $ar['campopk']='id';

    $ar['id']='new';
    $ar['n#usuarioid']=$param['usuarioid'];
    $ar['n#modulo_empresaid']=$param['modulo_empresaid'];

    $result=save_array_bd($ar); 
    return $result;
}

function get_rs_modulo_empresa_bd_byIds($param){
    $ar=array();
    $ar['tabla']='modulos_empresa'; 
    $ar['select#*']='id';
    $ar['n#empresaid']=$param['empresaid'];
    $ar['n#moduloid']=$param['moduloid'];
    $rs=select_array_bd($ar);
    return $rs;
}


function get_rs_modulo_usuario_bd_byIds($param){
    $ar=array();
    $ar['tabla']='modulos_usuarios'; 
    $ar['select#*']='id';
    $ar['n#usuarioid']=$param['usuarioid'];
    $ar['n#modulo_empresaid']=$param['modulo_empresaid'];
    $rs=select_array_bd($ar);
    return $rs;
}

function set_delete_modulo_empresa_bbdd($param){
    $ar=array();  
    $ar['tabla']='modulos_empresa'; 
    $ar['n#id']=$param['moduloempresaid'];

    $result=delete_array_bd($ar); 
    return $result;
}

function set_delete_depedencia_modulos_usuarios_bbdd($param){
    $ar=array();  
    $ar['tabla']='modulos_usuarios'; 
    $ar['n#modulo_empresaid']=$param['moduloempresaid'];
    
    $result=delete_array_bd($ar); 
    return $result;
}

function set_delete_modulo_usuarios_bbdd($param){
    $ar=array();  
    $ar['tabla']='modulos_usuarios'; 
    $ar['n#id']=$param['modulousuarioaid'];
    
    $result=delete_array_bd($ar); 
    return $result;
}
 
function get_modulos_usuarios_chk($idusuario){ 
    $sql="select  
    modem.id modulo_empresaid
   ,modu.nombre modulo
   ,IF(IFNULL(modus.usuarioid,0) = 0, 0, 1) chk			
   from modulos modu 
   left join (select modem.moduloid,modem.id 
              from modulos_empresa modem
              inner join empresas emp 
                 on modem.empresaid=emp.id_e 
               inner join usuarios us 
                 on emp.empresa=us.empresa
                and us.id = ".$idusuario.") modem 
     on modem.moduloid=modu.id 
   left join modulos_usuarios modus  
     on modus.modulo_empresaid=modem.id
     and modus.usuarioid = ".$idusuario."
   where modem.id is not null   
   order by modu.nombre";
 
    return get_registros($sql);
}


function set_procesar_checks_1Usuario_N_Modulos_post(){ //ojo en obras
    $ar=array();
    $ar_procesar=array();
    $resultFinal=true;
    $idusuario=get_PostorGet('idusuario');
    foreach($_POST as $field=>$value)
    {
        if(startsWith($field, 'hidden_')){                                                  
              $ar=explode("_", $field);
              $modulo_empresaid=$ar[1];
              $ar_procesar[$modulo_empresaid] = 0;
         }
    }
    foreach($_POST as $field=>$value)
    {
         if(startsWith($field, 'chkactivo_')){ 
                $ar=explode("_", $field);
                $modulo_empresaid=$ar[1];
                $ar_procesar[$modulo_empresaid]= strtolower($value)=='on' ? 1:0;
         }
    }
      
    foreach($ar_procesar as $modulo_empresaid=>$value)
    {
        $paramSelect=array();
        $paramSelect['usuarioid']=$idusuario;
        $paramSelect['modulo_empresaid']=$modulo_empresaid;
        $rs=get_rs_modulo_usuario_bd_byIds($paramSelect);

        $modulousuarioaid = count($rs) > 0 ? $rs[0]['id']: 0;
        if($value==1){ //check activado: se inserta permiso si no existe
            if($modulousuarioaid==0){
                $result=set_new_modulo_usuario_bbdd($paramSelect);
                if(!$result) $resultFinal=!$result;    
            }
        }else{
              if($modulousuarioaid>0){ //check desactivado: se borra permiso si existe
                $paramDelete=array();
                $paramDelete['modulousuarioaid']=$modulousuarioaid;
                $result=set_delete_modulo_usuarios_bbdd($paramDelete);
                if(!$result) $resultFinal=!$result;
              }    
        }
    }

    $ar['estado_escritura_bd']=$resultFinal;
    $ar['idusuario']=$idusuario;
    return $ar;   
}

function set_modulo_post(){    
    $isnew=$_POST['idmodulo']=='new' ? true:false;
    $rtn=array();
    $param=array();

    $param['idmodulo']=$_POST['idmodulo'];
    $param['nombre']=utf8_decode($_POST['modulo']);  
    $param['parametro']=$_POST['parametro'];

    if($isnew){
        $new_idmodulo=get_next_id_tabla('modulos');
    }
    
    $result=set_modulo_bbdd($param); 

    if($isnew){
        $param['idmodulo']=$new_idmodulo;
    }

    $rtn['estado_escritura_bd']=$result;
    $rtn['idmodulo']=$param['idmodulo'];
    
    return $rtn;
}

function set_modulo_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='modulos'; 
    $ar['campopk']='id';

    $ar['id']=$param['idmodulo'];
    $ar['s#nombre']=$param['nombre'];
    $ar['s#parametro']=$param['parametro'];

    $result=save_array_bd($ar); 
    return $result;
}

function get_modulos_para_proyectos($empresa){
    $sql="SELECT DISTINCT modu.id, modu.nombre 
          from modulos_empresa modem 
        inner join modulos modu 
           on modu.id=modem.moduloid
        inner join empresas emp 
           on modem.empresaid=emp.id_e 
        where emp.empresa='$empresa'
        and modu.parametro in ('".MODULO_PARAM_PEDIDOS_VERDE."','".MODULO_PARAM_PROYECTOS."') 
        order by modu.nombre"; 
    return get_registros($sql);
}

function get_modulos_para_clientes_entidad_tipo($empresa){
    $sql="SELECT DISTINCT modu.id, modu.nombre 
          from modulos_empresa modem 
        inner join modulos modu 
           on modu.id=modem.moduloid
        inner join empresas emp 
           on modem.empresaid=emp.id_e 
        where emp.empresa='$empresa'
        and modu.parametro in ('".MODULO_PARAM_CLIENTE.
                            "','".MODULO_PARAM_PROVEEDORES.
                            "','".MODULO_PARAM_PERSONAL.
                            "','".MODULO_PARAM_MODELO_DOCUMENTOS."') 
        order by modu.nombre"; 
    return get_registros($sql);
}

?>
