<?php


function get_afiliado_solo_cliente_byId($idafiliado){
    $sql="select 
             a.id_afiliado
            ,a.id_md5
            ,a.numero
            ,a.id_afiliado_padre
            ,a.id_empresa
            ,a.fecha_alta
            ,a.nombre
            ,a.dni
            ,a.contrasena
            ,a.direccion
            ,a.telefono
            ,a.email
            ,a.cliente_id
            ,a.estado
            ,a.cuenta_bancaria
            ,a.adjunto_cert_tit_cta
            ,a.swift_bic
            ,a.tipo_persona
            ,a.porc_retencion_irpf
            ,a.sexo
            ,a.edad
            ,a.pais
            ,ap.numero numeropadre
            ,ap.nombre nombrepadre
        from afiliados_solo_clientes a
        left join afiliados ap
         on a.id_afiliado_padre=ap.id_afiliado
        where a.id_afiliado=".$idafiliado;  

      return get_registros($sql);
}

function get_afiliados_solo_cliente_byIdEmpresaTree($idempresa){
  $sql="select 
           a.id_afiliado_padre
          ,a.id_afiliado
          ,a.numero
          ,a.nombre
          ,a.estado
          ,ap.numero numeropadre
          ,ap.nombre nombrepadre
       from afiliados_solo_clientes a 
       left join afiliados ap
       on a.id_afiliado_padre=ap.id_afiliado
       where a.id_empresa=".$idempresa
       ." and ap.id_empresa=".$idempresa
       ." order by a.id_afiliado_padre, a.id_afiliado";
       
   return get_registros($sql);
}

function sw_estado_afiliado_solo_cliente_activo($rw){
   return hasdato($rw['numero']) && $rw['estado']==1;
}


function set_afiliado_solo_cliente_perfil_felizia_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados_solo_clientes'; 
  $ar['id']=$param['id_afiliado'];
  $ar['campopk']='id_afiliado';
  $ar['s#nombre']=$param['nombre'];
  $ar['s#sexo']=$param['sexo'];
  $ar['n#edad']=$param['edad'];
  $ar['s#pais']=$param['pais'];
  
  $result=save_array_bd($ar); 
  return $result;
}
?>
