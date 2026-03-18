<?php 
 
require_once '../mysql.php';
require_once '../global_modelo.php';
require_once  !esSoloCliente() ? '../afiliados_modelo.php':'../afiliados_solo_clientes_modelo.php';
require_once '../empresa_modelo.php';

/* PayPal REST API configuration 
 * You can generate API credentials from the PayPal developer panel. 
 * See your keys here: https://developer.paypal.com/dashboard/ 
 */ 
define('PAYPAL_SANDBOX', TRUE); //TRUE=Sandbox | FALSE=Production 

define('PAYPALAUTHAPI_SANDBOX','https://api-m.sandbox.paypal.com/v1/oauth2/token'); 
define('PAYPALAPI_SANDBOX','https://api-m.sandbox.paypal.com/v2/checkout');

define('PAYPALAUTHAPI_PROD',  'https://api-m.paypal.com/v1/oauth2/token'); 
define('PAYPALAPI_PROD',  'https://api-m.paypal.com/v2/checkout');

//app prueba afiliados
// define('PAYPAL_SANDBOX_CLIENT_ID', 'AcSAnMdnwQOv2_HKOkyV_vVba0yLaeHxujtTaMF5FDuw7Yqw7uGNzNqNEFB_F7dxAc5Vf3FdOpTDzjf3'); 
// define('PAYPAL_SANDBOX_CLIENT_SECRET', 'ENOWAhPdg_K2I5hTBEWxN2xnkOaGD0AnvRfAoZxmbt_6XqkLRDid-VFHD16FxZ22xP_IO0SwNMqxneh9'); 

//app prueba2 afiliados
define('PAYPAL_SANDBOX_CLIENT_ID', 'ATaxX3AElhctszztSlw4_E1vUQr0rsALDbyY-Zu8zXye-M937f1VKZUJY945RYlzOx8jec4knonSnKnr'); 
define('PAYPAL_SANDBOX_CLIENT_SECRET', 'EJv5Alum8ujuHsj1urYIQAdTv61ijo4cJAVqtdy8cCGxp4JNTQBbh-gWSFliQPT5zPLXXbx_n3wPf7ie'); 

global $PAYPAL_CLIENT_ID, $PAYPAL_CLIENT_SECRET, $PAYPALAUTHAPI, $PAYPALAPI, $idafiliado;

conectar();
@$idafiliado=$_GET['idafiliado'];
$rs_afiliado=get_afiliado_bymd5($idafiliado); 
$rs_empresa=get_datos_empresa_factura($rs_afiliado[0]['id_empresa']);
desconectar();

if(PAYPAL_SANDBOX){
    @$PAYPAL_CLIENT_ID=PAYPAL_SANDBOX_CLIENT_ID;
    @$PAYPAL_CLIENT_SECRET=PAYPAL_SANDBOX_CLIENT_SECRET;
    @$PAYPALAUTHAPI=PAYPALAUTHAPI_SANDBOX;
    @$PAYPALAPI=PAYPALAPI_SANDBOX;
    
}else{
    @$PAYPAL_CLIENT_ID=$rs_empresa[0]['paypal_client_id_empresa'];
    @$PAYPAL_CLIENT_SECRET=$rs_empresa[0]['paypal_client_secret_empresa'];
    @$PAYPALAUTHAPI=PAYPALAUTHAPI_PROD;
    @$PAYPALAPI=PAYPALAPI_PROD;

    //ojo. esto es poruqe en modo PROD, si los ids de paypal configurados en la empresa son de sanbox, no funcionaría
    //@$PAYPALAUTHAPI=PAYPALAUTHAPI_SANDBOX;
    //@$PAYPALAPI=PAYPALAPI_SANDBOX;  
}


function esSoloCliente(){
  if(isset($_GET['sc']) && $_GET['sc']=='1'){
     return true;
  }
  return false;
}
?>