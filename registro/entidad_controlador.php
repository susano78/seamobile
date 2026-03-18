<?php
//Enumerado de entidades para la tabla de Clientes de bd
//La tabla de Clientes hay que verla como una tabla de entidades donde se irán registrando
//bajo de demanda. Campo de distinción Clientes.entidadid
//es decir, pueden registrarse clientes, proveedores, personal, etc...


define('ENTIDAD_CLIENTE',1);
define('ENTIDAD_PROVEEDOR',2);
define('ENTIDAD_PERSONAL',3);
define('ENTIDAD_MODELO_DOCUMENTOS',4); 

function getEntidad_ParamP($p){
    $ar=array();
    switch ($p){
        case MODULO_PARAM_CLIENTE:
            $ar['p']=$p;
            $ar['entidad']=ENTIDAD_CLIENTE;
        break;
        case MODULO_PARAM_PROVEEDORES:
            $ar['p']=MODULO_PARAM_CLIENTE;
            $ar['entidad']=ENTIDAD_PROVEEDOR;
        break;
        case MODULO_PARAM_PERSONAL:
            $ar['p']=MODULO_PARAM_CLIENTE;
            $ar['entidad']=ENTIDAD_PERSONAL;
        break;
        case MODULO_PARAM_MODELO_DOCUMENTOS:
            $ar['p']=MODULO_PARAM_CLIENTE;
            $ar['entidad']=ENTIDAD_MODELO_DOCUMENTOS;
        break;
        default:
            $ar['p']=$p;
            $ar['entidad']='';
        break;
    }
    return $ar;
}

function getNameSingularbyEntidad(){
    global $ENTIDAD;
    switch ($ENTIDAD){
        case ENTIDAD_CLIENTE:
         $name='cliente';
        break;
        case ENTIDAD_PROVEEDOR:
         $name='proveedor';
        break;
        case ENTIDAD_PERSONAL:
         $name='personal';
        break;
        case ENTIDAD_MODELO_DOCUMENTOS:
         $name='modelo documentos';
        break;
        default:
         $name='#####';
        break;
    }
    return $name;
}

function getNamePluralbyEntidad(){
    global $ENTIDAD;
    switch ($ENTIDAD){
        case ENTIDAD_CLIENTE:
         $name='clientes';
        break;
        case ENTIDAD_PROVEEDOR:
         $name='proveedores';
        break;
        case ENTIDAD_PERSONAL:
         $name='personal';
        break;
        case ENTIDAD_MODELO_DOCUMENTOS:
         $name='modelos documentos';
        break;
        default:
         $name='#####';
        break;
    }
    return $name;
}

function getNameParamPbyEntidad(){
    global $ENTIDAD;
    switch ($ENTIDAD){
        case ENTIDAD_CLIENTE:
         $name=MODULO_PARAM_CLIENTE;
        break;
        case ENTIDAD_PROVEEDOR:
         $name=MODULO_PARAM_PROVEEDORES;
        break;
        case ENTIDAD_PERSONAL:
         $name=MODULO_PARAM_PERSONAL;
        break;
        case ENTIDAD_MODELO_DOCUMENTOS:
         $name=MODULO_PARAM_MODELO_DOCUMENTOS;
        break;
        default:
         $name='#####';
        break;
    }
    return $name;
}

function get_titulo_tp_clientesEntidad($tp,$rs_tiposCliente_byEntidad){
   $retorno=$tp;
   global $ENTIDAD;
    switch ($ENTIDAD){
        case ENTIDAD_CLIENTE:
            $rs_tipos_param=$rs_tiposCliente_byEntidad['rs_'.MODULO_PARAM_CLIENTE];
        break;
        case ENTIDAD_PROVEEDOR:
            $rs_tipos_param=$rs_tiposCliente_byEntidad['rs_'.MODULO_PARAM_PROVEEDORES];
        break;
        case ENTIDAD_PERSONAL:
            $rs_tipos_param=$rs_tiposCliente_byEntidad['rs_'.MODULO_PARAM_PERSONAL];
        break;
        case ENTIDAD_MODELO_DOCUMENTOS:
            $rs_tipos_param=$rs_tiposCliente_byEntidad['rs_'.MODULO_PARAM_MODELO_DOCUMENTOS];
        break;
        default:
        break;
    }
    $n=count($rs_tipos_param);
    for($i=0;$i<$n;$i++){
            $rw=$rs_tipos_param[$i];
            if($rw['parametro']==$tp){
                return $rw['nombre'];
            }
    }
    return $retorno; 
}


?>