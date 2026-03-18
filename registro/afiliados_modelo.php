<?php

function get_datos_emisor_pedido($idafiliado){
    $sql="select 
         nombre nombre_empresa
        ,dni cif_empresa
        ,direccion direccion_empresa
        ,telefono telefono_empresa
        ,'' poblacion_empresa
        ,'' codigo_postal_empresa
        ,0 porc_retencion_irpf_empresa
        from afiliados
        where id_afiliado=".$idafiliado;  
      return get_registros($sql);
}

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
            ,ap.numero numeropadre
            ,ap.nombre nombrepadre
        from afiliados a
        left join afiliados ap
         on a.id_afiliado_padre=ap.id_afiliado
        where a.id_afiliado=".$idafiliado;  

      return get_registros($sql);
}


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
        from afiliados a
        left join afiliados ap
         on a.id_afiliado_padre=ap.id_afiliado
        where a.id_md5='".$idafiliadomdi5."'";  

      return get_registros($sql);
}

function get_sql_afiliados_byIdEmpresa($idempresa){
    $cond=get_cond_filtros_columnas_v2();
    $orderby=get_orderby_filtros_columnas(" a.numero ");
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
            ,a.estado
            ,ap.numero numeropadre
            ,ap.nombre nombrepadre
         from afiliados a 
         left join afiliados ap
         on a.id_afiliado_padre=ap.id_afiliado
         where a.id_empresa=".$idempresa.$cond.$orderby;

     return $sql;
}

function get_sql_afiliados_byIdEmpresaIdPatrocinador($idempresa,$idpatrocinador){ //queda en desuso hasta nueva orden
    $cond=get_cond_filtros_columnas_v2();
    $orderby=get_orderby_filtros_columnas(" a.numero ");
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
            ,a.estado
            ,ap.numero numeropadre
            ,ap.nombre nombrepadre
         from afiliados a 
         left join afiliados ap
         on a.id_afiliado_padre=ap.id_afiliado
         where a.id_afiliado_padre=".$idpatrocinador." 
         and a.id_empresa=".$idempresa.$cond.$orderby;

     return $sql;
}

function get_ultimoAfiliadoRegistrado_byIdEmpresa($idempresa){
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
            ,a.estado
            from afiliados a
            where a.numero=(select max(numero)
                            from afiliados 
                            where estado=1 and id_empresa=".$idempresa.")
            and a.id_empresa=".$idempresa;

         $rs=get_registros($sql);
         return count($rs)>0 ? $rs[0]: null;
}

function get_next_numero_afiliado($idempresa){
    $sql="select if(max(numero) is null, -1, max(numero) ) + 1 numero
    from afiliados
    where id_empresa=".$idempresa;
    $rs=get_registros($sql);
    return $rs[0]['numero'];
  }

  function get_arbol_tiene_nodo_raiz($idempresa){
    $sql="select count(*) cuenta
    from afiliados
    where estado=1
    and numero=0 
    and id_afiliado_padre is null 
    and id_empresa=".$idempresa;
    $rs=get_registros($sql);
    return ($rs[0]['cuenta'] > 0);
  }


  function get_afiliados_paraLiquidacion($idempresa){
    $sql="select 
         id_afiliado
        ,porc_retencion_irpf
        ,tipo_persona
         from afiliados 
         where estado=1 
         and numero is not null 
         and importe_pagado is not null
         and id_empresa=".$idempresa;

         return get_registros($sql);
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

function get_sql_afiliados_pagos_byIdEmpresa($idempresa){

$cond=get_cond_filtros_columnas_v2()
     .get_cond_desde_hasta('f.fecha'); 
$orderby=get_orderby_filtros_columnas(" f.id_factura desc ");

$sql="select 
           f.id_factura
          ,f.fecha fecha_factura
          ,f.numero numero_factura 
          ,f.total total_factura
          ,a.id_afiliado
          ,a.porc_retencion_irpf porc_retencion_irpf_afiliado
          ,a.nombre nombre_afiliado
          ,f.pagado
          ,u.usuario usuario_alta
          from ".TABLA_FACTURAS." f
          inner join afiliados a on f.emisor_cifnif=a.dni
          inner join empresas e on (f.cliente_cifnif=e.cif 
                                 or f.cliente_cifnif=e.cif_ext)
          left join usuarios u on f.id_usuario_alta=u.id                       
          and f.id_empresa=a.id_empresa
          and a.id_empresa=e.id_e
          where f.id_empresa=".$idempresa.$cond.$orderby;;

return $sql;
}



//de momento se actualiza cuenta bancaria y certificado de titulariad
function set_afiliado_update_post(){
  $rtn=array();
  $param=array();

  $fechayhoy=get_fechayhoy_ddmmyyyy();
  $carpeta='adjuntos_afiliados/';
  $estado_upload_ficheros['adjunto']='';
  $fichero_bd_adjunto_cert_tit_cta='';

  $idafiliado=get_idafiliado_login();

  //adjunto_cert_tit_cta
  $fichero_upload_destino_adjunto_cert_tit_cta = $idafiliado."_cert_tit_cta_".$fechayhoy;
  $result_upload_adjunto_cert_tit_cta=subir_fichero_post('adjunto_cert_tit_cta',RUTA_RAIZ_RELATIVA.$carpeta,$fichero_upload_destino_adjunto_cert_tit_cta); 
  if($result_upload_adjunto_cert_tit_cta['estado']=='OK'){
      $fichero_bd_adjunto_cert_tit_cta=$result_upload_adjunto_cert_tit_cta['mensaje'];
  }else{
    $estado_upload_ficheros['adjunto']=$result_upload_adjunto_cert_tit_cta['mensaje'];
  }
  //fin adjunto_cert_tit_cta

  if(hasdato($fichero_bd_adjunto_cert_tit_cta)){
      $param['adjunto_cert_tit_cta']=$fichero_bd_adjunto_cert_tit_cta;  
  }

  $param['id_afiliado']=$idafiliado;

  $param['cuenta_bancaria']=utf8_decode($_POST['cuenta_bancaria']); 
  $param['swift_bic']=utf8_decode($_POST['swift_bic']); 
  $param['porc_retencion_irpf']=getValueNumericOrDefault($_POST['porc_retencion_irpf'],0);

  $result=set_afiliado_update_bbdd($param); 

  $rs_afiliado=get_afiliado_byId($idafiliado); 
  $param['id_emp']= $rs_afiliado[0]['cliente_id'];
  $result_cliente=set_afiliado_update_cliente_bbdd($param); 

  $rtn['estado_upload_ficheros']=$estado_upload_ficheros;
  $rtn['estado_escritura_bd']=$result && $result_cliente; 

  return $rtn;
}

function set_afiliado_update_cliente_bbdd($param){ //se actualiza la cuenta bancaria al nivel cliente
  $ar=array();
  $ar['tabla']='clientes'; 
  $ar['id']=$param['id_emp'];
  $ar['campopk']='id_emp';
  if(isset($param['cuenta_bancaria'])){
      $ar['s#numero_cuenta']=$param['cuenta_bancaria'];
      $result=save_array_bd($ar); 
      return $result;
  }else{
      return true;
  }
}

function set_afiliado_update_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados'; 
  $ar['id']=$param['id_afiliado'];
  $ar['campopk']='id_afiliado';
  if(isset($param['cuenta_bancaria'])){
      $ar['s#cuenta_bancaria']=$param['cuenta_bancaria'];
  } 
  if(isset($param['adjunto_cert_tit_cta'])){ 
      $ar['s#adjunto_cert_tit_cta']=$param['adjunto_cert_tit_cta']; 
  } 
  if(isset($param['swift_bic'])){
    $ar['s#swift_bic']=$param['swift_bic'];
  }
  if(isset($param['porc_retencion_irpf'])){
    $ar['n#porc_retencion_irpf']=$param['porc_retencion_irpf'];
  }
  $result=save_array_bd($ar); 
  return $result;
}

//Cálculo de comisiones del arbol de afiliados
function construir_arbol($afiliados) {
  $arbol = [];
  $indices = [];
  
  // Crear estructura inicial para cada afiliado
  foreach ($afiliados as $afiliado) {
      $id = $afiliado['id_afiliado'];
      $padre = $afiliado['id_afiliado_padre'];
      $arbol[$id] = [
          'datos' => $afiliado,
          'hijos' => []
      ];
      $indices[$id] = &$arbol[$id];
  }

  // Asignar cada afiliado a su padre
  foreach ($afiliados as $afiliado) {
      $id = $afiliado['id_afiliado'];
      $padre = $afiliado['id_afiliado_padre'];
      if ($padre && isset($indices[$padre])) {
          $indices[$padre]['hijos'][] = &$indices[$id];
      }
  }

  return $arbol;

  // Retornar solo la raíz del árbol (afiliados sin padre)
  // return array_filter($arbol, function($nodo) {
  //   return !$nodo['datos']['id_afiliado_padre'];
  // });
}

function calcular_comisiones($nodo, $nivel = 1, $max_niveles = 8) {
  if ($nivel > $max_niveles) {
      return ['total' => 0, 'detalle' => []];
  }

  $total = 0;
  $detalle = [];

  foreach ($nodo['hijos'] as $hijo) {
      // Añadir la comisión por este hijo
      if(hasdato($hijo['datos']['numero'])){
        if(sw_estado_afiliado_activo($hijo['datos'])){ //si el referido no está activo, no se cobra comisión
              $total += 1;
              $detalle[] = [
                  'afiliado' => $hijo['datos']['id_afiliado'],
                  'id_afiliado_padre' => $hijo['datos']['id_afiliado_padre'],
                  'nivel' => $nivel,
                  'numero' => $hijo['datos']['numero'],
                  'nombre' => $hijo['datos']['nombre'],
                  'numeropadre' => $hijo['datos']['numeropadre'],
                  'nombrepadre' => $hijo['datos']['nombrepadre']           
              ];
        }
          // Calcular comisiones de los descendientes
          $resultado_hijo = calcular_comisiones($hijo, $nivel + 1, $max_niveles);
          $total += $resultado_hijo['total'];
          $detalle = array_merge($detalle, $resultado_hijo['detalle']);
      }
  }

  return ['total' => $total, 'detalle' => $detalle];
}



function listar_comisiones($arbol) {
  $listado = [];
  foreach ($arbol as $id_afiliado => $nodo) {
      $resultado = calcular_comisiones($nodo);
      if(sw_estado_afiliado_activo($nodo['datos'])){ //si no está activo, no se le calcula comisión
          $listado[$id_afiliado] = [
              'total' => $resultado['total'],
              'detalle' => $resultado['detalle']
          ];
    }
  }
  return $listado;
}

function listar_comisiones_bbdd($fecha_liquidacion,$idempresa){
     $rs=get_comisiones_arbol_totales_afiliado($fecha_liquidacion,$idempresa);
     $listado = [];
     $n=count($rs);
     for($i=0;$i<$n;$i++){ 
         $rw=$rs[$i];
         $id_afiliado=$rw['id_afiliado'];

         $detalle = [];
         $rsdetalle = get_comisiones_arbol_detalle_afiliado($id_afiliado,$fecha_liquidacion,$idempresa);
         $nd=count($rsdetalle);
         for($id=0;$id<$nd;$id++){ 
          $rwdetalle=$rsdetalle[$id];
          $detalle_afiliado[] = [
            'afiliado' => $id_afiliado,
            'id_afiliado_padre' => $rwdetalle['id_afiliado_padre'],
            'nivel' => $rwdetalle['nivel'],
            'numero' => $rwdetalle['numero'],
            'nombre' => $rwdetalle['nombre'],
            'numeropadre' => $rwdetalle['numeropadre'],
            'nombrepadre' => $rwdetalle['nombrepadre'] 
          ];           
          $detalle = array_merge($detalle, $detalle_afiliado);
          $detalle_afiliado = [];
         }

         $listado[$id_afiliado] = [
             'total' => $rw['total'],
             'detalle' => $detalle 
         ];
         unset($rsdetalle);
     }
     return $listado;
}

function get_comisiones_arbol_detalle_afiliado($id_afiliado,$fecha_liquidacion,$idempresa){
  $sql="SELECT   aca.id_afiliado_diferido id_afiliado
                ,aca.id_afiliado id_afiliado_padre
                ,ad.numero
                ,ad.nombre
                ,a.numero numeropadre
                ,a.nombre nombrepadre
                ,aca.nivel
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        INNER JOIN afiliados ad 
        ON aca.id_afiliado_diferido=ad.id_afiliado
        WHERE aca.id_afiliado=".$id_afiliado
        ." and a.id_empresa=".$idempresa
        ." and aca.fecha='".$fecha_liquidacion."'"
        ." order by aca.id_afiliado_diferido";

   return get_registros($sql);
}

function get_comisiones_arbol_totales_afiliado($fecha_liquidacion,$idempresa){
  $sql="SELECT aca.id_afiliado
              ,sum(aca.importe) total
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        WHERE a.id_empresa=".$idempresa
        ."  and aca.fecha='".$fecha_liquidacion."'"
        ."GROUP BY aca.id_afiliado";

   return get_registros($sql);
}

function set_comision_arbol_afiliados_update_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados_comision_arbol'; 
  $ar['id']='new';

  $ar['n#id_afiliado']=$param['id_afiliado'];
  $ar['n#id_afiliado_diferido']=$param['id_afiliado_diferido'];
  $ar['n#importe']=$param['importe'];
  $ar['s#fecha']=$param['fecha'];
  $ar['n#nivel']=$param['nivel'];
  $ar['n#id_cartera_afiliado']=$param['id_cartera_afiliado'];

  $ar['s#fecha_alta']=date("Y-n-j");
  $ar['n#id_usuario_alta']=get_idusuario_login();
  $result=save_array_bd($ar); 
  return $result;
}

function set_nuevo_registro_cartera_afiliado_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados_cartera'; 
  $ar['id']='new';

  $ar['n#id_afiliado']=$param['id_afiliado'];
  $ar['n#importe']=0;
  $ar['s#fecha']=$param['fecha'];
  $ar['s#concepto']=$param['concepto'];
  $ar['s#fecha_alta']=date("Y-n-j");
  $ar['n#id_usuario_alta']=get_idusuario_login();

  $new_id_cartera_afiliado=get_next_id_tabla('afiliados_cartera');
  $result['result']=save_array_bd($ar); 
  $result['new_id_cartera_afiliado']=$new_id_cartera_afiliado; 

  return $result;
}
function set_importe_registro_cartera_afiliado_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados_cartera'; 
  $ar['id']=$param['id_cartera_afiliado'];
  $ar['campopk']='id_cartera_afiliado';
  $ar['n#importe']=$param['importe'];
  $result=save_array_bd($ar); 
  return $result;
}

function dameFechaLiquidacion_desde_post(){
    $mes_liquidacion=$_POST['mes_liquidacion'];
    $primero_mes='1/'.$mes_liquidacion;
    $fecha_liquidacion = str_replace('/','-',$primero_mes);
    $fecha_liquidacion = dd_mm_yyyy_to_yyyy_mm_dd($fecha_liquidacion); 
    $fecha_liquidacion = ultimoDiaDelMes($fecha_liquidacion);
    return $fecha_liquidacion;
}

function crear_facturas_comisiones_arbol_afiliados($listado_comisiones, $fecha_liquidacion){ //ojo pediente testear
  $idempresa=get_idempresa_login();

  $rs_empresa_configuracion=get_datos_empresa_factura($idempresa);
  $rw_empresa=$rs_empresa_configuracion[0];

  $anio=get_anio_from_yyyy_mm_dia($fecha_liquidacion);
  $mes=get_mes_from_yyyy_mm_dia($fecha_liquidacion);
  $arMesesPorNumero=get_arMesesPorNumero();
  $mes_año=$arMesesPorNumero[(int)$mes].'/'.$anio;

  foreach ($listado_comisiones as $id_afiliado => $datos) {
      $rs_comisiones_arbol_detalle=get_comisiones_arbol_detalle_afiliado_facturar($id_afiliado,$fecha_liquidacion,$idempresa);

      if(count($rs_comisiones_arbol_detalle) > 0){
        $rw_comision=$rs_comisiones_arbol_detalle[0];
        $param_varios=array();
        $param_varios['pagado']=0;
        $param_varios['id_empresa']=$idempresa;
        $param_varios['porc_retencion_irpf']=$rw_comision['porc_retencion_irpf'];
        $param_varios['fecha_factura']=$fecha_liquidacion;

        $param_cliente=array();
        $param_cliente['id_cliente']='null';

        $ar_cliente=get_cliente_factura_pago_afiliado($rw_empresa
                                                     ,$rw_comision['porc_retencion_irpf']
                                                     ,$rw_comision['tipo_persona']);

        $param_cliente['nombre']=$ar_cliente['nombre_empresa'];  
        $param_cliente['cifnif']=$ar_cliente['cif_empresa'];
        $param_cliente['direccion']=$ar_cliente['direccion_empresa'];
        $param_cliente['poblacion']=$ar_cliente['poblacion_empresa'];
        $param_cliente['codigo_postal']=$ar_cliente['codigo_postal_empresa'];
        $param_cliente['telefono']=$ar_cliente['telefono_empresa'];

        $param_emisor=array();
        $param_emisor['nombre']=$rw_comision['nombre'];  
        $param_emisor['cifnif']=$rw_comision['cifnif']; 
        $param_emisor['direccion']=$rw_comision['direccion'];
        $param_emisor['poblacion']=$rw_comision['poblacion'];
        $param_emisor['codigo_postal']=$rw_comision['codigo_postal'];
        $param_emisor['emisor_telefono']=$rw_comision['telefono'];

        $porcentajeIGIC=7;
        $precio_unidad=calcularPrecioUnidad($rw_comision['total'], $porcentajeIGIC);
        $rs_detalle=array();
        $rs_detalle[0]['codigo']='';
        $rs_detalle[0]['concepto']='Comisión Red de afiliados '.$mes_año;
        $rs_detalle[0]['unidades']=1;
        $rs_detalle[0]['precio_unidad']=$precio_unidad;
        $rs_detalle[0]['subtotal']=$precio_unidad;
        $rs_detalle[0]['porc_impuesto']=$porcentajeIGIC;

        $result=crear_factura_automatica($param_varios
                                      , $param_cliente
                                      , $param_emisor
                                      , $rs_detalle);

        if($result['result']) {
                $result_norma34=generar_norma34($result['new_id_factura']
                                               ,$rs_empresa_configuracion
                                               ,$fecha_liquidacion);

                logger::log('##### RESULTADO NORMA34 IDFACTURA:'.$result['new_id_factura'].' -> '.$result_norma34);                               
        }                              
      }
  }

  return $result['result'];
}

function get_comisiones_arbol_detalle_afiliado_facturar($id_afiliado,$fecha_liquidacion,$idempresa){
  $sql="SELECT   a.numero 
                ,if(a.nombre is not null,a.nombre,c.cliente) nombre
                ,if(a.dni is not null,a.dni,c.cif) cifnif
                ,if(a.direccion is not null,a.direccion,c.direccion) direccion
                ,if(a.telefono is not null,a.telefono,c.telefono) telefono
                ,a.porc_retencion_irpf
                ,a.tipo_persona
                ,c.poblacion
                ,c.provincia
                ,c.cp codigo_postal
                ,sum(aca.importe) total
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        LEFT JOIN clientes c 
        ON a.cliente_id=c.id_emp
        WHERE aca.id_afiliado=".$id_afiliado
        ." and a.id_empresa=".$idempresa
        ." and aca.fecha='".$fecha_liquidacion."'"
        ." HAVING sum(aca.importe) is not null";

   return get_registros($sql);
}

function guardar_comisiones_arbol_afiliados($listado_comisiones,$fecha_liquidacion) {
  $result=false;
  $arMesesPorNumero=get_arMesesPorNumero();
  $anio=get_anio_from_yyyy_mm_dia($fecha_liquidacion);
  $mes=get_mes_from_yyyy_mm_dia($fecha_liquidacion);
  $mes_año=$arMesesPorNumero[(int)$mes].'/'.$anio;

  foreach ($listado_comisiones as $id_afiliado => $datos) {
    $param=array();
    $param['id_afiliado'] = $id_afiliado;
    $param['fecha'] = $fecha_liquidacion;
    $param['concepto'] = utf8_decode('Comisiones Red Afiliados '.$mes_año);
    $result_cartera=set_nuevo_registro_cartera_afiliado_bbdd($param);
    if($result_cartera['result']){
        $importe=0;

        foreach ($datos['detalle'] as $detalle) {
          $param=array();
          $param['id_afiliado'] = $id_afiliado;
          $param['id_afiliado_diferido'] = $detalle['afiliado'];
          $param['importe'] = 1;
          $param['fecha'] = $fecha_liquidacion;
          $param['nivel'] = $detalle['nivel'];
          $param['id_cartera_afiliado']=$result_cartera['new_id_cartera_afiliado'];
          $result=set_comision_arbol_afiliados_update_bbdd($param);
          if($result) $importe += 1;
        }

        $param=array();
        $param['id_cartera_afiliado'] = $result_cartera['new_id_cartera_afiliado'];
        $param['importe']=$importe;
        $result=set_importe_registro_cartera_afiliado_bbdd($param);
    }

  }
  return $result;
}

function existen_comisiones_guardadas_arbol($fecha_liquidacion,$idempresa){
  $sql="SELECT count(*) cuenta
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        WHERE a.id_empresa=".$idempresa
        ."  and aca.fecha='".$fecha_liquidacion."'";
   $rs=get_registros($sql);
   return ($rs[0]['cuenta'] > 0);
}



function calcular_comisiones_arbol_afiliados($rs_afiliados_arbol) {
    $retorno='';
    $arbol = construir_arbol($rs_afiliados_arbol);
    $fecha_liquidacion=dameFechaLiquidacion_desde_post();
    if(!existen_comisiones_guardadas_arbol($fecha_liquidacion,get_idempresa_login())){
      $listado = listar_comisiones($arbol);
      $result = guardar_comisiones_arbol_afiliados($listado, $fecha_liquidacion);
      $result_facturas = crear_facturas_comisiones_arbol_afiliados($listado, $fecha_liquidacion);
    }
    $listado = listar_comisiones_bbdd($fecha_liquidacion, get_idempresa_login());  
    // Mostrar el listado
    foreach ($listado as $id_afiliado => $datos) {
        $sw=true;
        $contador=0;
        $lineaPadre = "<br><h2 class='page-title'>[afiliado] Total Comisión: <strong>" . $datos['total'].((int)$datos['total'] > 1 ? " euros " :" euro ")."</strong> </h2>";
        foreach ($datos['detalle'] as $detalle) {
          $contador+=1;
          if($sw){
            $linea=$lineaPadre;
            $linea=str_replace('[afiliado]', utf8_encode($detalle['nombrepadre'])." (".$detalle['numeropadre'].")", $linea);
            $retorno.=$linea;
            $sw=false;
          }
          $retorno.= "<strong>".$contador." - ".utf8_encode($detalle['nombre'])." (".$detalle['numero'].")"." (Nivel ". $detalle['nivel'].")</strong><br>";
        }
    }

    return $retorno;
}

//Fin Cálculo de comisiones del arbol de afiliados

function set_afiliado_pass_update_post(){
  $rtn=array();
  $param=array();

  $idafiliado=soy_usuario_afiliado() ? get_idafiliado_login() : $_POST['id_afiliado'];

  $param['id_afiliado']=$idafiliado;
  $param['contrasena']=utf8_decode($_POST['contrasena']); 

  $result=set_afiliado_pass_update_bbdd($param); 
  $rtn['estado_escritura_bd']=$result; 

  return $rtn;
}

function set_afiliado_pass_update_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados'; 
  $ar['id']=$param['id_afiliado'];
  $ar['campopk']='id_afiliado';
  $ar['s#contrasena']=$param['contrasena'];
  $result=save_array_bd($ar); 
  return $result;
}

function sw_estado_afiliado_activo($rw){
   return hasdato($rw['numero']) && $rw['estado']==1;
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

function get_color_estado_afiliado($rw){
  return sw_estado_afiliado_activo($rw) ? 'verde':'rojo';
}

function sw_afiliado_tiene_numero($rw){
  return hasdato($rw['numero']);
}

function set_afiliado_estado_update_post(){
  $rtn=array();
  $param=array();

  $idafiliado=$_POST['id_afiliado'];
  $param['id_afiliado']=$idafiliado;

  $rs_afiliado=get_afiliado_byId($idafiliado); 
  $estado_actual=$rs_afiliado[0]['estado'];
  $param['estado']=$estado_actual==0 ? 1:0;

  $result=set_afiliado_estado_update_bbdd($param); 
  if($result){
    $logparam=array();
    $logparam['tipo_actividad']=TIPO_ACTIVIDAD_CAMBIO_ESTADO_AFILIADO;
    $logparam['estado_actual']=$estado_actual;
    $logparam['estado_nuevo']=$param['estado'];
    $logparam['motivo']=utf8_decode($_POST['motivo']);
    $logparam['rw_afiliado']=$rs_afiliado[0];
    $logresult=insertar_log_actividad_afiliado($logparam);
  }

  $rtn['estado_escritura_bd']=$result; 

  return $rtn;
}

function set_afiliado_estado_update_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados'; 
  $ar['id']=$param['id_afiliado'];
  $ar['campopk']='id_afiliado';
  $ar['n#estado']=$param['estado'];
  $result=save_array_bd($ar); 
  return $result;
}


function get_comisiones_fecha_arbol_totales_afiliado($id_afiliado,$idempresa){
  $sql="SELECT aca.fecha
              ,sum(aca.importe) total
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        WHERE a.id_empresa=".$idempresa
        ."  and aca.id_afiliado=".$id_afiliado
        ." GROUP BY aca.fecha 
           ORDER BY aca.fecha desc";

   return get_registros($sql);
}

function listar_comisiones_fecha_afiliado_bbdd($id_afiliado,$idempresa){
  $rs=get_comisiones_fecha_arbol_totales_afiliado($id_afiliado,$idempresa);
  $listado = [];
  $n=count($rs);
  for($i=0;$i<$n;$i++){ 
      $rw=$rs[$i];
      $fecha_liquidacion=$rw['fecha'];

      $detalle = [];
      $rsdetalle = get_comisiones_arbol_detalle_afiliado($id_afiliado,$fecha_liquidacion,$idempresa);
      $nd=count($rsdetalle);
      for($id=0;$id<$nd;$id++){ 
       $rwdetalle=$rsdetalle[$id];
       $detalle_afiliado[] = [
         'afiliado' => $id_afiliado,
         'id_afiliado_padre' => $rwdetalle['id_afiliado_padre'],
         'nivel' => $rwdetalle['nivel'],
         'numero' => $rwdetalle['numero'],
         'nombre' => $rwdetalle['nombre'],
         'numeropadre' => $rwdetalle['numeropadre'],
         'nombrepadre' => $rwdetalle['nombrepadre'] 
       ];           
       $detalle = array_merge($detalle, $detalle_afiliado);
       $detalle_afiliado = [];
      }

      $listado[$fecha_liquidacion] = [
          'total' => $rw['total'],
          'detalle' => $detalle 
      ];
      unset($rsdetalle);
  }
  return $listado;
}

function get_resultado_comisiones_arbol_byAfiliado($id_afiliado,$idempresa) {
  $retorno='';
  $listado = listar_comisiones_fecha_afiliado_bbdd($id_afiliado, $idempresa);  
  $arMesesPorNumero=get_arMesesPorNumero();
  // Mostrar el listado
  foreach ($listado as $fecha_liquidacion => $datos) {
      $anio=get_anio_from_yyyy_mm_dia($fecha_liquidacion);
      $mes=get_mes_from_yyyy_mm_dia($fecha_liquidacion);
      $mes_año=$arMesesPorNumero[(int)$mes].'/'.$anio;
      $sw=true;
      $contador=0;
      $lineaPadre = "<br><h2 class='page-title'><strong>[mes_año_liquidacion]</strong> - Total Comisión: <strong>" . $datos['total'].((int)$datos['total'] > 1 ? " euros " :" euro ")."</strong> </h2>";
      foreach ($datos['detalle'] as $detalle) {
        $contador+=1;
        if($sw){
          $linea=$lineaPadre;
          $linea=str_replace('[mes_año_liquidacion]',   $mes_año, $linea);
          $retorno.=$linea;
          $sw=false;
        }
        $retorno.= "<strong>".$contador." - ".utf8_encode($detalle['nombre'])." (".$detalle['numero'].")"." (Nivel ". $detalle['nivel'].")</strong><br>";
      }
  }

  return hasdato($retorno) ? $retorno : '<strong>No dispone de comisiones</strong>';
}

function get_sql_afiliado_movimientos_cartera($id_afiliado){
  $sql="SELECT 
         ac.id_cartera_afiliado
        ,ac.id_afiliado
        ,ac.fecha
        ,ac.importe
        ,ac.concepto
        ,(SELECT SUM(bac.importe)
        FROM afiliados_cartera bac
        WHERE bac.id_afiliado = ac.id_afiliado 
          AND (bac.fecha < ac.fecha
             OR (bac.fecha = ac.fecha AND bac.id_cartera_afiliado <= ac.id_cartera_afiliado))) saldo
    FROM afiliados_cartera ac
    WHERE ac.id_afiliado = ".$id_afiliado
    ." ORDER BY ac.fecha DESC, id_cartera_afiliado DESC";       

   return $sql;
}


function get_resultado_comisiones_arbol_byEmpresa($idempresa) {
  $retorno='';
  $listado = listar_comisiones_fecha_empresa_bbdd($idempresa);  
  $arMesesPorNumero=get_arMesesPorNumero();
  // Mostrar el listado
  foreach ($listado as $fecha_liquidacion => $datos) {
      $anio=get_anio_from_yyyy_mm_dia($fecha_liquidacion);
      $mes=get_mes_from_yyyy_mm_dia($fecha_liquidacion);
      $mes_año=$arMesesPorNumero[(int)$mes].'/'.$anio;
      $sw=true;
      $contador=0;
      $lineaPadre = "<br><h2 class='page-title'><strong>[mes_año_liquidacion]</strong> - Total Comisión: <strong>" . $datos['total'].((int)$datos['total'] > 1 ? " euros " :" euro ")."</strong> </h2>";
      foreach ($datos['detalle'] as $detalle) {
        $contador+=1;
        if($sw){
          $linea=$lineaPadre;
          $linea=str_replace('[mes_año_liquidacion]',   $mes_año, $linea);
          $retorno.=$linea;
          $sw=false;
        }
        $retorno.= "<strong>".$contador." - ".utf8_encode($detalle['nombre'])." (".$detalle['numero'].")"." (Total Comisión: ". $detalle['total_afiliado'].((int)$detalle['total_afiliado'] > 1 ? " euros " :" euro ").")</strong><br>";
      }
  }

  return hasdato($retorno) ? $retorno : '<strong>No hay comisiones</strong>';
}

function listar_comisiones_fecha_empresa_bbdd($idempresa){
  $rs=get_comisiones_fecha_arbol_totales_empresa($idempresa);
  $listado = [];
  $n=count($rs);
  for($i=0;$i<$n;$i++){ 
      $rw=$rs[$i];
      $fecha_liquidacion=$rw['fecha'];

      $detalle = [];
      $rsdetalle = get_comisiones_arbol_detalle_empresa($fecha_liquidacion,$idempresa);
      $nd=count($rsdetalle);
      for($id=0;$id<$nd;$id++){ 
       $rwdetalle=$rsdetalle[$id];
       $detalle_mes[] = [
         'afiliado' => $rwdetalle['id_afiliado'],
         'numero' => $rwdetalle['numero'],
         'nombre' => $rwdetalle['nombre'],
         'total_afiliado' => $rwdetalle['total_afiliado']
       ];           
       $detalle = array_merge($detalle, $detalle_mes);
       $detalle_mes = [];
      }

      $listado[$fecha_liquidacion] = [
          'total' => $rw['total'],
          'detalle' => $detalle 
      ];
      unset($rsdetalle);
  }
  return $listado;
}

function get_comisiones_arbol_detalle_empresa($fecha_liquidacion,$idempresa){
  $sql="SELECT   aca.id_afiliado
                ,a.numero
                ,a.nombre
                ,sum(aca.importe) total_afiliado
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        WHERE a.id_empresa=".$idempresa
        ." and aca.fecha='".$fecha_liquidacion."'"
        ." group by aca.id_afiliado
                     ,a.numero
                     ,a.nombre
           order by aca.id_afiliado";

   return get_registros($sql);
}

function get_comisiones_fecha_arbol_totales_empresa($idempresa){
  $sql="SELECT aca.fecha
              ,sum(aca.importe) total
        FROM afiliados_comision_arbol aca
        INNER JOIN afiliados a 
        ON aca.id_afiliado=a.id_afiliado
        WHERE a.id_empresa=".$idempresa
        ." GROUP BY aca.fecha 
           ORDER BY aca.fecha desc";

   return get_registros($sql);
}

function user_permiso_mod_afiliados(){
    return has_user_modulo(MODULO_PARAM_AFILIADOS);
}

function set_comision_concepto_afiliados_update_bbdd($param){ 
  $ar=array();
  $ar['tabla']='afiliados_comision_conceptos'; 
  $ar['id']='new';

  $ar['n#id_afiliado']=$param['id_afiliado'];
  $ar['n#id_pedido']=$param['id_pedido'];
  $ar['n#id_concepto']=$param['id_concepto'];
  $ar['n#importe_base']=$param['importe_base'];
  $ar['n#importe_comision']=$param['importe_comision'];
  $ar['n#porc_comision']=$param['porc_comision'];
  $ar['s#fecha']=$param['fecha'];
  $ar['n#id_cartera_afiliado']=$param['id_cartera_afiliado'];

  $ar['s#fecha_alta']=date("Y-n-j");
  $ar['n#id_usuario_alta']=get_idusuario_login();
  $result=save_array_bd($ar); 
  return $result;
}

function dameNombreTipoPersona($tipo_persona){
   if($tipo_persona==1) return 'Física';
   return 'Jurídica';
}

function dameLiteralEtiquetaDNICIF($tipo_persona){
  if($tipo_persona==1) return 'D.N.I.';
  return 'C.I.F';
}

?>
