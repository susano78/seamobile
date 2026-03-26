<?php

function get_afiliado_bymd5($idafiliadomdi5){
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
            ,a.cuenta_bancaria
            ,a.adjunto_cert_tit_cta
            ,ap.numero numeropadre
            ,ap.nombre nombrepadre
        from afiliados_solo_clientes a
        left join afiliados_solo_clientes ap
         on a.id_afiliado_padre=ap.id_afiliado
        where a.id_md5='".$idafiliadomdi5."'";  

      return get_registros($sql);
}

function get_next_numero_afiliado($idempresa){
    $sql="select if(max(numero) is null, -1, max(numero) ) + 1 numero
    from afiliados_solo_clientes
    where id_empresa=".$idempresa;
    $rs=get_registros($sql);
    return $rs[0]['numero'];
  }
?>
