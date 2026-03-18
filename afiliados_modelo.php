<?php
function get_afiliado_byId($idafiliado){
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
        from afiliados a
        left join afiliados ap
         on a.id_afiliado_padre=ap.id_afiliado
        where a.id_afiliado=".$idafiliado;  

      return get_registros($sql);
}

function set_afiliado_perfil_felizia_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados'; 
  $ar['id']=$param['id_afiliado'];
  $ar['campopk']='id_afiliado';
  $ar['s#nombre']=$param['nombre'];
  $ar['s#sexo']=$param['sexo'];
  $ar['n#edad']=$param['edad'];
  $ar['s#pais']=$param['pais'];
  
  $result=save_array_bd($ar); 
  return $result;
}

function sw_estado_afiliado_activo($rw){
   return hasdato($rw['numero']) && $rw['estado']==1;
}

function get_color_estado_afiliado($rw){
  return sw_estado_afiliado_activo($rw) ? 'verde':'rojo';
}

function get_nombre_estado_afiliado($rw){
  $rtn='BLOQUEADO';
  if (sw_estado_afiliado_activo($rw)){
     $rtn='ACTIVO';
  }else{
     if(!hasdato($rw['numero'])) $rtn='BLOQUEADO SIN NÚMERO';
  }
  return '<strong>'.$rtn.'</strong>';
}

function get_afiliados_byIdEmpresaTree($idempresa){
  $sql="select 
           a.id_afiliado_padre
          ,a.id_afiliado
          ,a.numero
          ,a.nombre
          ,a.estado
          ,ap.numero numeropadre
          ,ap.nombre nombrepadre
       from afiliados a 
       left join afiliados ap
       on a.id_afiliado_padre=ap.id_afiliado
       where a.id_empresa=".$idempresa
       ." order by a.id_afiliado_padre, a.id_afiliado";
   return get_registros($sql);
}

?>
