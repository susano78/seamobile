<?php 
 require_once '../logger.php';
 require_once '../mysql.php';
 require_once '../global_controlador.php';
 require_once '../global_modelo.php';
 require_once '../entidad_controlador.php';
 require_once  !esSoloCliente() ? '../afiliados_modelo.php':'../afiliados_solo_clientes_modelo.php';
 require_once '../spadm_modelo.php';
 require_once '../empresa_modelo.php';

$payment_ref_id = $statusMsg = ''; 
$status = 'error'; 
$statusMsg = utf8_decode("Transacción fallida!");
 
// Check whether the payment ID is not empty 
if(!empty($_GET['checkout_ref_id'])){ 
    $payment_txn_id  = base64_decode($_GET['checkout_ref_id']); 
    $item_number  = $_GET['item_number']; 
    $amount_value  = $_GET['amount_value'];  

    conectar();
    $rs_afiliado=get_afiliado_bymd5($item_number); //es el md5 del afiliado
    $new_numero_afiliado=get_next_numero_afiliado($rs_afiliado[0]['id_empresa']);
    $rs_empresa=get_empresaById($rs_afiliado[0]['id_empresa']);
    $rs_empresa_configuracion=get_datos_empresa_factura($rs_afiliado[0]['id_empresa']);
    $cuota_mensual_prefacturas=$rs_empresa_configuracion[0]['importe_cliente_empresa']; //para el afiliado y el solo cliente
    $empresa=$rs_empresa[0]['empresa'];
    $generar_registros=false;

    if($rs_afiliado[0]['numero']==''){
            $errorbd='';
            $ar=array();
            $ar['tabla']=getTablaAfiliados(); 
            $ar['id']=$rs_afiliado[0]['id_afiliado'];
            $ar['campopk']='id_afiliado';
            $ar['n#numero']=$new_numero_afiliado;
            $ar['s#fecha_hora_vigencia']=date('Y-m-d H:i:s');
            $ar['n#estado']=1;    
            $ar['n#importe_pagado']=$amount_value; 
            $result=save_array_bd($ar); 
            if(!$result){
                $errorbd='<br>'.utf8_decode('Error al guardar el número de afiliado');
            }else{
                $generar_registros=true; 
            }
    }else{
        $new_numero_afiliado=$rs_afiliado[0]['numero'];
    }

    if($generar_registros){
            $result=crear_cliente_sino_existe($rs_afiliado,$empresa, $cuota_mensual_prefacturas);
            if($result['ok']==1){
                $id_cliente=$result['id_emp'];
                if($result['nuevo']==1){
                    set_cliente_al_afiliado_bbdd($rs_afiliado[0]['id_afiliado'], $id_cliente);
                }

                $result=crear_factura($rs_afiliado
                                    , $rs_afiliado[0]['id_empresa']
                                    , $rs_empresa_configuracion
                                    , $id_cliente
                                    , $amount_value);

                $result=crear_prefacturas($rs_afiliado
                                            ,$rs_afiliado[0]['id_empresa']
                                            ,$rs_empresa_configuracion
                                            ,$id_cliente
                                            ,$cuota_mensual_prefacturas);
            }
    }

    desconectar();
         
     $status = 'success'; 
     $statusMsg = 'La trasacción se ha realizado con éxito!'.$errorbd; 
}else{ 
    $statusMsg = 'La trasacción ha fallado!'; 
} 

function crear_prefacturas($rs_afiliado, $id_empresa, $rs_empresa_configuracion, $id_cliente, $importe){
    $param=array();
    $rw=$rs_afiliado[0];
    $rw_empresa_configuracion=$rs_empresa_configuracion[0];

    $param['id_empresa']=$id_empresa;

    $param['cliente_id']=$id_cliente;
    $param['cliente_nombre']=$rw['nombre'];  
    $param['cliente_cifnif']=$rw['dni'];
    $param['cliente_direccion']=$rw['direccion'];
    $param['cliente_telefono']=$rw['telefono'];

    $param['emisor_nombre']=$rw_empresa_configuracion['nombre_empresa'];  
    $param['emisor_cifnif']=$rw_empresa_configuracion['cif_empresa'];  
    $param['emisor_direccion']=$rw_empresa_configuracion['direccion_empresa'];
    $param['emisor_poblacion']=$rw_empresa_configuracion['poblacion_empresa'];
    $param['emisor_codigo_postal']=$rw_empresa_configuracion['codigo_postal_empresa'];
    $param['emisor_telefono']=$rw_empresa_configuracion['telefono_empresa'];

    $arMeses=get_arMeses();
    foreach($arMeses as $mes=>$valor){
       if($valor<=12){ 
            $param['mes']=$valor;
            $result_cabecera=set_cabecera_factura_premesa_bbdd($param);
            if($result_cabecera['result']){

                $porcentajeIGIC=7;
                $precio_unidad=calcularPrecioUnidad($importe, $porcentajeIGIC);

                $param_detalle=array();
                $param_detalle['id_factura_premesa']=$result_cabecera['new_id_factura_premesa'];
                $param_detalle['codigo']=''; 
                $param_detalle['concepto']=(!esSoloCliente() ? utf8_decode('Suscripción de afiliado') : utf8_decode('Suscripción de cliente'));
                $param_detalle['unidades']=1;
                $param_detalle['precio_unidad']=$precio_unidad;
                $param_detalle['subtotal']=$precio_unidad;
                $param_detalle['porc_impuesto']=$porcentajeIGIC;
                $param_detalle['importe_impuesto']=($precio_unidad*$porcentajeIGIC)/100;
                $param_detalle['total']=$importe;
                $result_detalle=set_detalle_factura_premesa_bbdd($param_detalle);

            }

            if($result_cabecera['result'] && $result_detalle){
                $resultCalculo=calcularTotalesfacturaPremesa($result_cabecera['new_id_factura_premesa']);
                $result_totales['result']=$resultCalculo['result'];
            
                if($result_totales['result']){
                    $paramTotales=array();
                    $paramTotales['id_factura_premesa']=$result_cabecera['new_id_factura_premesa'];
                    $paramTotales['subtotal']=$resultCalculo['subtotal'];
                    $paramTotales['total_impuesto']=$resultCalculo['total_impuesto'];
                    $paramTotales['total']=$resultCalculo['total'];
        
                    $result_totales['result']=set_totales_factura_premesa_bbdd($paramTotales);
                }
            
            }
       }
    }

   return $result_cabecera['result'] && $result_detalle && $result_totales['result'];
}

function set_totales_factura_premesa_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='facturasPremesa'; 
    $ar['campopk']='id_factura_premesa';
    $ar['id']=$param['id_factura_premesa'];

    $ar['n#subtotal']=$param['subtotal'];
    $ar['n#total_impuesto']=$param['total_impuesto'];
    $ar['n#total']=$param['total'];

    $result=save_array_bd($ar); 

    return $result;
}

function calcularTotalesfacturaPremesa($id_factura_premesa){
    $resultCalculo=array();
    $rs=get_CalculoTotalesfacturaPremesa_bd($id_factura_premesa);
    $rw=$rs[0];

    $resultCalculo['subtotal']=$rw['subtotal'];
    $resultCalculo['total_impuesto']=$rw['total_impuesto'];
    $resultCalculo['total']=$rw['total'];
    $resultCalculo['result']=true;
    return $resultCalculo;  
}

function get_CalculoTotalesfacturaPremesa_bd($idfacturaPremesa){
    $sql="select 
     sum(subtotal) subtotal
    ,sum(importe_impuesto) total_impuesto
    ,sum(total) total
    from facturasPremesaDetalle
    where id_factura_premesa=".$idfacturaPremesa;

    return get_registros($sql);
}
function set_cabecera_factura_premesa_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='facturasPremesa'; 
    $ar['id']='new';
    
    $ar['n#id_empresa']=$param['id_empresa'];
    $ar['s#fecha_alta']=date("Y-n-j");
    $ar['n#borrar']=0;

    $ar['n#mes']=$param['mes'];

    $ar['s#emisor_nombre']=$param['emisor_nombre'];  
    $ar['s#emisor_cifnif']=$param['emisor_cifnif'];
    $ar['s#emisor_direccion']=$param['emisor_direccion'];
    $ar['s#emisor_poblacion']=$param['emisor_poblacion'];
    $ar['s#emisor_codigo_postal']=$param['emisor_codigo_postal'];
    $ar['s#emisor_telefono']=$param['emisor_telefono'];

    $ar['n#cliente_id']=$param['cliente_id']; 
    $ar['s#cliente_nombre']=$param['cliente_nombre'];  
    $ar['s#cliente_cifnif']=$param['cliente_cifnif'];
    $ar['s#cliente_direccion']=$param['cliente_direccion'];
    $ar['s#cliente_telefono']=$param['cliente_telefono'];

    $new_id_factura_premesa=get_next_id_tabla('facturasPremesa');
    
    $result['result']=save_array_bd($ar); 

    if($result['result'])
        $result['new_id_factura_premesa']=$new_id_factura_premesa; 

    return $result;
}

function set_detalle_factura_premesa_bbdd($param){
    $ar=array();
    $ar['tabla']='facturasPremesaDetalle'; 
    $ar['id']='new';

    $ar['n#id_factura_premesa']=$param['id_factura_premesa']; 
    $ar['s#codigo']=$param['codigo'];  
    $ar['s#concepto']=$param['concepto'];
    $ar['n#unidades']=$param['unidades'];
    $ar['n#precio_unidad']=$param['precio_unidad'];
    $ar['n#subtotal']=$param['subtotal'];
    $ar['n#porc_impuesto']=$param['porc_impuesto'];
    $ar['n#importe_impuesto']=$param['importe_impuesto'];

    $ar['n#total']=$param['total'];

    $result=save_array_bd($ar); 

    return $result;
}

function crear_factura($rs_afiliado, $id_empresa, $rs_empresa_configuracion, $id_cliente, $importe){
 $param=array();
 $rw=$rs_afiliado[0];
 $rw_empresa_configuracion=$rs_empresa_configuracion[0];

 $param['id_empresa']=$id_empresa;
 $param['fecha_factura']=date( "Y-n-j");

 $param['cliente_id']=$id_cliente; 
 $param['cliente_nombre']=$rw['nombre'];  
 $param['cliente_cifnif']=$rw['dni'];
 $param['cliente_direccion']=$rw['direccion'];
 $param['cliente_telefono']=$rw['telefono'];
 $param['pagado']=1;

 $param['emisor_nombre']=$rw_empresa_configuracion['nombre_empresa'];  
 $param['emisor_cifnif']=$rw_empresa_configuracion['cif_empresa'];  
 $param['emisor_direccion']=$rw_empresa_configuracion['direccion_empresa'];
 $param['emisor_poblacion']=$rw_empresa_configuracion['poblacion_empresa'];
 $param['emisor_codigo_postal']=$rw_empresa_configuracion['codigo_postal_empresa'];
 $param['emisor_telefono']=$rw_empresa_configuracion['telefono_empresa'];

 $result_cabecera=set_cabecera_factura_bbdd($param);
 if($result_cabecera['result']){

    $porcentajeIGIC=7;
    $precio_unidad=calcularPrecioUnidad($importe, $porcentajeIGIC);

    $param=array();
    $param['id_factura']=$result_cabecera['new_id_factura'];
    $param['codigo']=''; 
    $param['concepto']=(!esSoloCliente() ? utf8_decode('Suscripción de afiliado') : utf8_decode('Suscripción de cliente'));
    $param['unidades']=1;
    $param['precio_unidad']=$precio_unidad;
    $param['subtotal']=$precio_unidad;
    $param['porc_impuesto']=$porcentajeIGIC;
    $param['importe_impuesto']=($precio_unidad*$porcentajeIGIC)/100;
    $param['total']=$importe;
    $result_detalle=set_detalle_factura_bbdd($param);
 }

 if($result_cabecera['result'] && $result_detalle){
    $resultCalculo=calcularTotalesfactura($result_cabecera['new_id_factura']);
    $result_totales['result']=$resultCalculo['result'];

    if($result_totales['result']){
           $paramTotales=array();
           $paramTotales['id_factura']=$result_cabecera['new_id_factura'];
           $paramTotales['subtotal']=$resultCalculo['subtotal'];
           $paramTotales['total_impuesto']=$resultCalculo['total_impuesto'];
           $paramTotales['total']=$resultCalculo['total'];
           $paramTotales['porc_retencion_irpf']=$resultCalculo['porc_retencion_irpf'];
           $paramTotales['importe_retencion_irpf']=$resultCalculo['importe_retencion_irpf']; 
           
           $result_totales['result']=set_totales_factura_bbdd($paramTotales);
    }

}

return $result_cabecera['result'] && $result_detalle && $result_totales['result'];
}

function calcularPrecioUnidad($importeTotal, $porcentajeIGIC) {
    // Calcula el precio unitario antes del IGIC
    $precioUnidad = $importeTotal / (1 + $porcentajeIGIC / 100);
    return $precioUnidad;
}

function calcularTotalesfactura($id_factura){
    $resultCalculo=array();
    $rs=get_CalculoTotalesfactura_bd($id_factura);
    $rw=$rs[0];

    $resultCalculo['subtotal']=$rw['subtotal'];

    $porc_retencion_irpf=0;
    $importe_retencion_irpf=0;

    $resultCalculo['porc_retencion_irpf']=$porc_retencion_irpf;
    $resultCalculo['importe_retencion_irpf']=$importe_retencion_irpf;                                           
    $resultCalculo['total_impuesto']=$rw['total_impuesto'];
    $resultCalculo['total']=$rw['total'] - $importe_retencion_irpf;
    $resultCalculo['result']=true;
    return $resultCalculo;  
}

function get_CalculoTotalesfactura_bd($idfactura){
    $sql="select 
     sum(subtotal) subtotal
    ,sum(importe_impuesto) total_impuesto
    ,sum(total) total
    from facturasDetalle
    where id_factura=".$idfactura;

    return get_registros($sql);
}

function set_totales_factura_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='facturas'; 
    $ar['campopk']='id_factura';
    $ar['id']=$param['id_factura'];

    $ar['n#subtotal']=$param['subtotal'];
    $ar['n#total_impuesto']=$param['total_impuesto'];
    $ar['n#total']=$param['total'];
    $ar['n#porc_retencion_irpf']=$param['porc_retencion_irpf'];
    $ar['n#importe_retencion_irpf']=$param['importe_retencion_irpf'];

    $result=save_array_bd($ar); 

    return $result;
}

function set_detalle_factura_bbdd($param){
    $ar=array();
    $ar['tabla']='facturasDetalle'; 
    $ar['id']='new';

    $ar['n#id_factura']=$param['id_factura']; 
    $ar['s#codigo']=$param['codigo'];  
    $ar['s#concepto']=$param['concepto'];
    $ar['n#unidades']=$param['unidades'];
    $ar['n#precio_unidad']=$param['precio_unidad'];
    $ar['n#subtotal']=$param['subtotal'];
    $ar['n#porc_impuesto']=$param['porc_impuesto'];
    $ar['n#importe_impuesto']=$param['importe_impuesto'];
    $ar['n#total']=$param['total'];
    $result=save_array_bd($ar); 
    return $result;
}

function set_cabecera_factura_bbdd($param){
    $result=array();
    $ar=array();
    
    $ar['tabla']='facturas'; 
    $ar['id']='new';
    
    $ar['n#id_empresa']=$param['id_empresa'];
    $ar['n#numero']=get_next_numero_factura(get_anio_from_yyyy_mm_dia($param['fecha_factura'])
                                          , $param['id_empresa']);
    $ar['s#fecha_alta']=date("Y-n-j");
    $ar['s#fecha']=$param['fecha_factura'];

    $ar['s#emisor_nombre']=$param['emisor_nombre'];  
    $ar['s#emisor_cifnif']=$param['emisor_cifnif'];
    $ar['s#emisor_direccion']=$param['emisor_direccion'];
    $ar['s#emisor_poblacion']=$param['emisor_poblacion'];
    $ar['s#emisor_codigo_postal']=$param['emisor_codigo_postal'];
    $ar['s#emisor_telefono']=$param['emisor_telefono'];

    $ar['n#cliente_id']=$param['cliente_id']; 
    $ar['s#cliente_nombre']=$param['cliente_nombre'];  
    $ar['s#cliente_cifnif']=$param['cliente_cifnif'];
    $ar['s#cliente_direccion']=$param['cliente_direccion'];
    $ar['s#cliente_telefono']=$param['cliente_telefono'];
    $ar['n#pagado']=$param['pagado'];

    $new_id_factura=get_next_id_tabla('facturas');
    $result['result']=save_array_bd($ar); 

    if($result['result'])
        $result['new_id_factura']=$new_id_factura; 

    return $result;
}

function get_next_numero_factura($anio, $id_empresa){ 
    $sql="select if(max(numero) is null, 0, max(numero) ) + 1 numero
          from facturas
          where id_empresa=".$id_empresa."
          and year(fecha)=".$anio;
    $rs=get_registros($sql);
    return $rs[0]['numero'];
}

function crear_cliente_sino_existe($rs_afiliado, $empresa, $cuota){
   $result=array();
   $rw=$rs_afiliado[0];
   $swAltaCliente=false;

   $param_cliente=array();
   $param_cliente['empresa']=$empresa; 
   $param_cliente['cif']=$rw['dni'];
   $param_cliente['nombre']=$rw['nombre']; 

    if(hasdato($rw['dni'])){
        $rs_cliente=get_rs_cliente_bd_byCif($param_cliente);
        if(count($rs_cliente) > 0){ //cliente buscado por dnicif
            $cliente_id=$rs_cliente[0]['id_emp'];
        }else{ $swAltaCliente=true; }
    }
    if(hasdato($rw['nombre']) && !$swAltaCliente){
        $rs_cliente=get_rs_cliente_bd_byNombre($param_cliente);
        if(count($rs_cliente) > 0){ //cliente buscado por nombre
            $cliente_id=$rs_cliente[0]['id_emp'];
        }else{ $swAltaCliente=true; }
    }

    if($swAltaCliente){
            $param=array(); 
            $param['nombre']=$rw['nombre'];
            $param['cif']=$rw['dni'];
            $param['direccion']=$rw['direccion'];
            $param['telefono']=$rw['telefono'];
            $param["empresa"]=$empresa;
            $param['numero_cuenta']=$rw['cuenta_bancaria'];
            $param['cuota']=$cuota;
            $param['email']=$rw['email'];
            $result=alta_cliente_alpago_afiliado_bbdd($param);
            return $result;
    }else{
        $result['id_emp']=$cliente_id; 
        $result['ok']=1;
        $result['nuevo']=0;
        return $result;
    }
}

function alta_cliente_alpago_afiliado_bbdd($param){
    $result=array();
    $ar=array();
    $ar['tabla']='clientes'; 
    $ar['id']='new';
    $ar['campopk']='id_emp';

    $ar['s#fecha']=date("Y-n-j");
    $ar['s#activo']='SI';
    $ar['s#remesar']='SI';
    $ar['s#tipo']='cliente';
    $ar['s#empresa']=$param["empresa"];
    $ar['n#identidad']=ENTIDAD_CLIENTE;

    $ar['s#cliente']=$param['nombre'];  
    $ar['s#cif']=$param['cif'];
    $ar['s#direccion']=$param['direccion'];
    $ar['s#telefono']=$param['telefono'];
    $ar['s#email']=$param["email"];
    $ar['s#email_facturacion']=$param["email"];
    $ar['s#numero_cuenta']=$param['numero_cuenta'];
    $ar['n#cuota_alta']=$param['cuota'];
    $ar['n#cuota_mes']=$param['cuota'];

    $new_id_cliente=get_next_id_tabla('clientes');
    $result_save=save_array_bd($ar); 

    if($result_save){
        $result['id_emp']=$new_id_cliente; 
        $result['ok']=1;
        $result['nuevo']=1;
    }

    return $result;
}

function set_cliente_al_afiliado_bbdd($id_afiliado,$id_cliente)
{
    $ar=array();
    $ar['tabla']=getTablaAfiliados(); 
    $ar['id']=$id_afiliado;
    $ar['campopk']='id_afiliado';
    $ar['n#cliente_id']=$id_cliente; 
    $result=save_array_bd($ar); 
    return $result;
}

function get_rs_cliente_bd_byCif($param){
    $ar=array();
    $ar['tabla']='clientes'; 
    $ar['select#*']='id_emp';
    $ar['s#empresa']=$param['empresa'];
    $ar['s#cif']=$param['cif'];
    $rs=select_array_bd($ar);
    return $rs;
}

function get_rs_cliente_bd_byNombre($param){
    $ar=array();
    $ar['tabla']='clientes'; 
    $ar['select#*']='id_emp';
    $ar['s#empresa']=$param['empresa'];
    $ar['s#cliente']=$param['nombre'];
    $rs=select_array_bd($ar);
    return $rs;
}

function esSoloCliente(){
  if(isset($_GET['sc']) && $_GET['sc']=='1'){
     return true;
  }
  return false;
}

function esRenovacionToken(){
  if(isset($_GET['tk']) && $_GET['tk']=='1'){
     return true;
  }
  return false;
}

function getTablaAfiliados(){
  $tabla='afiliados';
  if(esSoloCliente()) {
    $tabla='afiliados_solo_clientes';
  }
  return $tabla;
}

?>
<style>  
.hidden {
visibility: hidden;
}
.centrado {
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.letra { font-size: 150% }
</style>
<?php if(!empty($payment_txn_id)){ ?>
    <div class="panel centrado letra">
            <h1 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>
            
            <h4><?php echo 'Información del Pago'; ?></h4>
            <p><b>Referencia de la <?php echo 'trasacción'; ?>:</b> <?php echo $payment_txn_id; ?></p>
            <p><b><?php echo  esSoloCliente() ? 'Número de Cliente':'Número de Afiliado'; ?>:</b> <?php echo $new_numero_afiliado; ?></p>
            <p><b>Importe:</b> <?php echo $amount_value.' Euros'; ?></p>
            <br><br>
            <a href="../../index.php?p=cerrar_sesion&empresa=<?=$empresa;?>" target="_blank">Acceda a su perfil</a>
    </div>

<?php }else{ ?>
    <div class="panel centrado letra">
    <h1 class="error">Error en el pago!</h1>
    <p class="error"><?php echo $statusMsg; ?></p>
    <br><br>
    <a href="../../index.php?p=cerrar_sesion&empresa=<?=$empresa;?>" target="_blank">Acceda a su perfil</a>
    </div>
<?php } ?>