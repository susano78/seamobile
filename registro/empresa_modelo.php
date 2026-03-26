<?php

define('CARPETA_LOGOS_EMPRESA','logos/');

function set_datos_empresa_factura($param){
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';
    $ar['id']=$param['empresaid'];
    $ar['s#nombre']=$param['nombre_empresa'];
    $ar['s#cif']=$param['cif_empresa'];
    $ar['s#direccion']=$param['direccion_empresa'];
    $ar['s#poblacion']=$param['poblacion_empresa'];
    $ar['s#codigo_postal']=$param['codigo_postal_empresa'];
    $ar['s#telefono']=$param['telefono_empresa'];
   
    $result=save_array_bd($ar); 
    return $result;
}

function set_datos_empresa_ext_factura($param){
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';
    $ar['id']=$param['empresaid'];
    $ar['s#nombre_ext']=$param['nombre_empresa_ext'];
    $ar['s#cif_ext']=$param['cif_empresa_ext'];
    $ar['s#direccion_ext']=$param['direccion_empresa_ext'];
    $ar['s#poblacion_ext']=$param['poblacion_empresa_ext'];
    $ar['s#codigo_postal_ext']=$param['codigo_postal_empresa_ext'];
    $ar['s#telefono_ext']=$param['telefono_empresa_ext'];
    $ar['s#swift_bic']=$param['cuenta_swift_bic_empresa'];
   
    $result=save_array_bd($ar); 
    return $result;
}

function get_datos_empresa_factura($empresaid){
    $sql="select 
           nombre nombre_empresa
          ,cif cif_empresa
          ,direccion direccion_empresa
          ,poblacion poblacion_empresa
          ,codigo_postal codigo_postal_empresa
          ,telefono telefono_empresa
          ,cuenta_iban cuenta_iban_empresa
          ,norma19_bic norma19_bic_empresa
          ,pie_factura pie_factura_empresa
          ,norma19_sufijo norma19_sufijo_empresa
          ,datos_registrales datos_registrales_empresa
          ,porc_retencion_irpf porc_retencion_irpf_empresa
          ,codigo_a3con codigo_a3con_empresa
          ,importe_afiliado importe_afiliado_empresa
          ,importe_cliente importe_cliente_empresa
          ,condiciones_afiliado condiciones_afiliado_empresa
          ,paypal_client_id paypal_client_id_empresa
          ,paypal_client_secret paypal_client_secret_empresa
          ,nombre_ext nombre_empresa_ext
          ,cif_ext cif_empresa_ext
          ,direccion_ext direccion_empresa_ext
          ,poblacion_ext poblacion_empresa_ext
          ,codigo_postal_ext codigo_postal_empresa_ext
          ,telefono_ext telefono_empresa_ext
          ,swift_bic cuenta_swift_bic_empresa
          from empresas
          where id_e=".$empresaid;

     return get_registros($sql);
   }

   function get_cliente_factura_pago_afiliado($rw_empresa, $porc_irpf, $tipo_persona){
    $retorno=array();
    $retorno['nombre_empresa']=$rw_empresa['nombre_empresa'];  
    $retorno['cif_empresa']=$rw_empresa['cif_empresa'];
    $retorno['direccion_empresa']=$rw_empresa['direccion_empresa'];
    $retorno['poblacion_empresa']=$rw_empresa['poblacion_empresa'];
    $retorno['codigo_postal_empresa']=$rw_empresa['codigo_postal_empresa'];
    $retorno['telefono_empresa']=$rw_empresa['telefono_empresa'];

    if($porc_irpf==0 && $tipo_persona==1){ 
        //si afiliado tiene irpf a 0 y es persona física, el cliente de la factura es la empresa extranjera
        $retorno['nombre_empresa']=$rw_empresa['nombre_empresa_ext'];  
        $retorno['cif_empresa']=$rw_empresa['cif_empresa_ext'];
        $retorno['direccion_empresa']=$rw_empresa['direccion_empresa_ext'];
        $retorno['poblacion_empresa']=$rw_empresa['poblacion_empresa_ext'];
        $retorno['codigo_postal_empresa']=$rw_empresa['codigo_postal_empresa_ext'];
        $retorno['telefono_empresa']=$rw_empresa['telefono_empresa_ext'];
    }
    return $retorno;
   }

   function get_empresa_byNombre($nombre_empresa){
    $sql="select *
          from empresas
          where empresa='".$nombre_empresa."'";
    
     return get_registros($sql);
   }

   function set_datos_otros_empresa_factura($param){
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';
    $ar['id']=$param['empresaid'];
    $ar['s#cuenta_iban']=$param['cuenta_iban_empresa'];
    $ar['s#norma19_bic']=$param['norma19_bic_empresa'];
    $ar['s#norma19_sufijo']=$param['norma19_sufijo_empresa'];
    $ar['s#pie_factura']=$param['pie_factura_empresa'];
    $ar['n#porc_retencion_irpf']=$param['porc_retencion_irpf_empresa'];
    $ar['s#codigo_a3con']=$param['codigo_a3con_empresa'];

    $result=save_array_bd($ar); 
    return $result;
}

function set_config_afiliados_empresa($param){
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';
    $ar['id']=$param['empresaid'];
    $ar['n#importe_afiliado']=$param['importe_afiliado'];
    $ar['s#paypal_client_id']=$param['paypal_client_id'];
    $ar['s#paypal_client_secret']=$param['paypal_client_secret'];

    if (isset($param['condiciones_afiliado'])){
         $ar['s#condiciones_afiliado']=$param['condiciones_afiliado'];
    }
    $result=save_array_bd($ar); 
    return $result;
}

function set_logo_empresa($param){
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';
    $ar['id']=$param['empresaid'];
    $ar['s#logo']=$param['rutalogo'];

    $result=save_array_bd($ar); 
    return $result;
}

function set_datos_registrales_empresa_factura($param){
    $ar=array();
    $ar['tabla']='empresas'; 
    $ar['campopk']='id_e';
    $ar['id']=$param['empresaid'];
    $ar['s#datos_registrales']=$param['datos_registrales_empresa'];

    $result=save_array_bd($ar); 
    return $result;
}



?>
