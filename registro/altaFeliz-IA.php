<?php 

  // https://www.feliz-ia.com/registro/altaFeliz-IA.php?idafiliado=e894d5c7a8afee3e8fbb850cadc92082



define('RUTA_RAIZ_RELATIVA','./intranet/');
define('RUTA_RAIZ_CONDICIONES','http://www.grupodesoluciones.com/intranet/');
include('mysql.php');
conectar();
 
$empresaid='';
$idafiliado_md5='';
$swEnvioNotificacionAlta=false;
$swAlta=false;
$mensajeEnvioNotifi='';
$mensajeValidacion='';
$new_id_md5='';

$name_patrocinador='';
$name_empresa_patrocinador='';
$condiciones_afiliados_empresa='#';

if(esSoloCliente()){
  if(isset($_GET['idcliente'])){
    $idafiliado_md5=$_GET['idcliente']; 
  }else{
    $idafiliado_md5=$_POST['idcliente'];  
  }
}else{
  if(isset($_GET['idafiliado'])){
    $idafiliado_md5=$_GET['idafiliado']; 
  }else{
    $idafiliado_md5=$_POST['idafiliado'];  
  }
}

$id_afiliado_padre=0;
$idafiliado_md5=trim($idafiliado_md5);
$rs_afiliado_padre=get_patrocinadorbyref($idafiliado_md5);
$arbol_tiene_nodo_raiz=false;
if(count($rs_afiliado_padre)>0){
    $arbol_tiene_nodo_raiz=true;
    $rw_afiliado_padre=$rs_afiliado_padre[0];
    $name_patrocinador=utf8_encode($rw_afiliado_padre['nombre']);
    $id_afiliado_padre=$rw_afiliado_padre['id_afiliado'];

    $rs_empresa=get_empresa_byId($rw_afiliado_padre['id_empresa']);
    $name_empresa_patrocinador=utf8_encode($rs_empresa[0]['nombre']);

    if(hasdato($rs_empresa[0]['condiciones_afiliado']))
       $condiciones_afiliados_empresa=RUTA_RAIZ_CONDICIONES.$rs_empresa[0]['condiciones_afiliado'];
}else{

  if(isset($_POST['e'])){   
      $empresaid=$_POST['e']; 
  }else{
      $empresaid=base64_decode($_GET['e']);   
  }

  if(hasdato($empresaid)){
      $arbol_tiene_nodo_raiz=get_arbol_tiene_nodo_raiz($empresaid);
  } 
}

if(!isset($_POST['envioAltaAfiliado']) && $id_afiliado_padre==0 && $idafiliado_md5!=md5('nodo_raiz')){
   echo (esSoloCliente() ? "Parámetro idcliente incorrecto":"Parámetro idafiliado incorrecto"); exit;
}

if(!isset($_POST['envioAltaAfiliado']) && $arbol_tiene_nodo_raiz && $idafiliado_md5==md5('nodo_raiz')){
  echo "Parámetros incoherentes";exit;
}

$titulo="FELIZ-IA. Registro de Usuario";
if(!$arbol_tiene_nodo_raiz && $idafiliado_md5==md5('nodo_raiz')){
  $titulo.=" Inicial";
}

if(!$arbol_tiene_nodo_raiz && $idafiliado_md5==md5('nodo_raiz')){
  $rw_afiliado_padre=array();
  $rw_afiliado_padre['id_empresa']=$empresaid;
  $rw_afiliado_padre['nombre']='';
  $rw_afiliado_padre['id_afiliado']='NULL';
  $name_patrocinador=utf8_encode($rw_afiliado_padre['nombre']);
  $rs_empresa=get_empresa_byId($rw_afiliado_padre['id_empresa']);
  $name_empresa_patrocinador=utf8_encode($rs_empresa[0]['nombre']);
}

$acepta_condiciones = $_POST['acepta_condiciones']=='on' ? true:false;

if($_POST['envioAltaAfiliado']==1 
&& (   ($id_afiliado_padre>0 && $idafiliado_md5!=md5('nodo_raiz'))
    || ($id_afiliado_padre==0 && $idafiliado_md5==md5('nodo_raiz'))
   )
&& $acepta_condiciones){
    
      $idafiliadoretorno=0;
      $sw=validarRegistroPost($mensajeValidacion,$rw_afiliado_padre['id_empresa']);
      if($sw){
        $sw=guardar_afiliado($mensajeEnvioNotifi,$idafiliadoretorno,$new_id_md5,$rw_afiliado_padre);
        if($sw){  
          $swAlta=true;
          //$destinatarios='soy_fenix@hotmail.com#anibal.jcz@gmail.com'; 
          $destinatarios='anibal.jcz@gmail.com'; //ojo depurada
          $ar_destinatarios=explode('#',$destinatarios);
          $bodyhtml=getBodyNotificacion($idafiliadoretorno,$rw_afiliado_padre);
          $sw=enviar_notificacionAlta($ar_destinatarios,$bodyhtml,$mensajeEnvioNotifi); 
          
          if($sw){
              $swEnvioNotificacionAlta=true; 
          }
        }
      }else{
        $mensajeEnvioNotifi=$mensajeValidacion;
      }
}

if($swAlta){
  if(esSoloCliente()) {
        header("Location: paypal_afiliados/index.php?sc=1&idafiliado=".$new_id_md5);
  }else{
        header("Location: paypal_afiliados/index.php?idafiliado=".$new_id_md5);
  }   
  exit;
}

  desconectar(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?=$titulo;?></title>

<!-- Mantengo todas tus librerías -->
<link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" href="fonts/font-awesome-4/css/font-awesome.min.css">
<!-- Favicon (icono del navegador) -->
<link rel="icon" href="/feliz-ia-favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/feliz-ia-favicon.ico" type="image/x-icon">
	
<style>
*{box-sizing:border-box;margin:0;padding:0}

body{
  font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
  background:#f5f7fb;
  color:#1f2933;
}

.app{
  min-height:100vh;
  max-width:480px;
  margin:0 auto;
  padding-bottom:40px;
}

/* Barra superior */
.top-bar{
  background:#0f172a;
  color:#fff;
  padding:14px 16px;
  font-size:15px;
  font-weight:600;
}

/* Tarjeta */
.card{
  background:#fff;
  margin:14px;
  padding:16px;
  border-radius:16px;
  box-shadow:0 4px 10px rgba(15,23,42,.08);
}

.card-title{
  font-size:18px;
  font-weight:600;
  margin-bottom:14px;
}

/* Formulario */
.form-group{
  margin-bottom:14px;
}

label{
  font-size:13px;
  font-weight:600;
  margin-bottom:4px;
  display:block;
}

.form-control{
  border-radius:999px;
  padding:10px 14px;
  font-size:14px;
  border:1px solid #d1d5db;
}

textarea.form-control{
  border-radius:12px;
}

/* Radios y check */
.radio-inline,
.checkbox-inline{
  font-size:14px;
}

/* Botón */
.btn-primary{
  width:100%;
  border-radius:999px;
  padding:12px;
  font-size:15px;
  font-weight:600;
  background:#2563eb;
  border:none;
}

.btn-primary:hover{
  background:#1e40af;
}

/* Alertas */
.alert{
  margin:12px;
  border-radius:12px;
  font-size:14px;
}

/* Responsive desktop */
@media(min-width:768px){
  .app{max-width:680px}
}
</style>
</head>

<body>

<div class="app">

  <!-- Barra superior -->
  <div class="top-bar">
   Feliz-IA
  </div>

  <?php if($_POST['envioAltaAfiliado']==1 && !$swAlta){
      if(!$acepta_condiciones){
               $mensajeEnvio='Deber aceptar condiciones del programa de incentivos';
               }else{
               $mensajeEnvio='Ha habido un error al realizar el registro.'.$mensajeEnvioNotifi;
      } ?>
  <div class="alert alert-danger">
    <strong>Error:</strong> <?=$mensajeEnvio;?>
  </div>
  <?php } ?>

  <!-- Tarjeta formulario -->
  <div class="card">
    <div class="card-title">Registro de Usuario</div>

    <form
      name="form_alta_afiliado"
      id="form_alta_afiliado"
      action="<?=get_url_post();?>"
      method="POST"
      enctype="multipart/form-data"
      role="form"
    >

      <div class="form-group">
        <label>Empresa</label>
        <input type="text" class="form-control" readonly value="<?=$name_empresa_patrocinador;?>">
      </div>

      <div class="form-group">
        <label>Patrocinador</label>
        <input type="text" class="form-control" readonly value="<?=$name_patrocinador;?>">
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="solo_cliente" <?php if(esSoloCliente()){?> checked="checked" <?php }?>>
          Sólo cliente</a>
        </label>
      </div>

      <div class="form-group">
        <label>Nombre y Apellidos / Razón social</label>
        <input type="text" class="form-control" name="nombre_apellidos" required>
      </div>

      <div class="form-group">
        <label>DNI / CIF (Va a ser tu USUARIO)</label>
        <input type="text" class="form-control" name="dni" required>
      </div>

      <div class="form-group">
        <label>Contraseña</label>
        <input type="text" class="form-control" name="contrasena" required>
      </div>

      <div class="form-group">
        <label>Dirección</label>
        <textarea class="form-control" name="direccion" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>Teléfono</label>
        <input type="text" class="form-control" name="telefono">
      </div>

      <div class="form-group">
        <label>E-mail</label>
        <input type="email" class="form-control" name="email" required>
      </div>

      <div class="form-group">
        <label>Tipo Persona</label><br>
        <label class="radio-inline">
          <input type="radio" name="tipo_persona" value="1" checked> Física
        </label>
        <label class="radio-inline">
          <input type="radio" name="tipo_persona" value="2"> Jurídica
        </label>
      </div>

      <div class="form-group">
        <label>Cuenta SWIFT/BIC</label>
        <input type="text" class="form-control" name="swift_bic">
      </div>

      <div class="form-group">
        <label>Cuenta Bancaria (Para recibir las comisiones, y para el cargo mensual de 11€/mes)</label>
        <input type="text" class="form-control" name="cuenta_bancaria">
      </div>

      <div class="form-group">
        <label>Certificado Titularidad Bancaria</label>
        <input type="file" name="adjunto_cert_tit_cta">
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="acepta_condiciones" required>
          Acepto las <a href="<?=$condiciones_afiliados_empresa;?>" target="_blank">condiciones</a>
        </label>
      </div>

      <input type="hidden" name="envioAltaAfiliado" value="1">
      <input type="hidden" name="idafiliado" value="<?=$idafiliado_md5;?>">
      <input type="hidden" name="idcliente" value="<?=$idafiliado_md5;?>">
      <input type="hidden" name="e" value="<?=$empresaid;?>">

      <button type="submit" class="btn btn-primary">
        Enviar registro
      </button>

    </form>
  </div>

</div>

<!-- JS originales -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>


<?php 

function get_url_post(){
	$url=$_SERVER['REQUEST_URI'];
	$url=str_replace('/intranet/','',$url);
        
        $keys = array("error","success");
        $url=remove_url_query_args($url,$keys);
	return $url;
}

function remove_url_query_args($url,$keys=array()) {
        $url_parts = parse_url($url);
        if(empty($url_parts['query'])) return $url;
                
        parse_str($url_parts['query'], $result_array);
        foreach ( $keys as $key ) { unset($result_array[$key]); }
        $url_parts['query'] = http_build_query($result_array);
        $url = (isset($url_parts["scheme"])?$url_parts["scheme"]."://":"").
                (isset($url_parts["user"])?$url_parts["user"].":":"").
                (isset($url_parts["pass"])?$url_parts["pass"]."@":"").
                (isset($url_parts["host"])?$url_parts["host"]:"").
                (isset($url_parts["port"])?":".$url_parts["port"]:"").
                (isset($url_parts["path"])?$url_parts["path"]:"").
                (isset($url_parts["query"])?"?".$url_parts["query"]:"").
                (isset($url_parts["fragment"])?"#".$url_parts["fragment"]:"");
        return $url;
}


function get_registros($sql) {
    global $link;

    $retorno = [];

    $result = mysqli_query($link, $sql);
    if (!$result) {
        return $retorno;
    }

    while ($fila = mysqli_fetch_array($result, MYSQLI_BOTH)) {
        $retorno[] = $fila;
    }

    return $retorno;
}
 
function get_patrocinadorbyref($idafiliado_md5){
     $sql="SELECT * FROM afiliados where id_md5='".$idafiliado_md5."'";
     return get_registros($sql);
}

function get_arbol_tiene_nodo_raiz($empresaid){
  $sql="select count(*) cuenta
  from afiliados
  where numero=0 
  and id_afiliado_padre is null 
  and id_empresa=".$empresaid;
  $rs=get_registros($sql);
  return ($rs[0]['cuenta'] > 0);
}

function get_empresa_byId($empresaid){
  $sql="select 
        empresa nombre 
       ,condiciones_afiliado
        from empresas
        where id_e=".$empresaid;

   return get_registros($sql);
 }

function get_afiliadobyid($id){
     $sql="SELECT * FROM ".getTablaAfiliados()." a 
     left join empresas e on a.id_empresa=e.id_e
     where a.id_afiliado=".$id;
     return get_registros($sql);
}

function existeDni($dni,$id_empresa){
  $sql="SELECT count(*) cuenta FROM ".getTablaAfiliados()." 
  where id_empresa=".$id_empresa." 
  and dni='".$dni."'";
  $rs=get_registros($sql);
  return $rs[0]['cuenta'] > 0 ? true:false;
}

function validarRegistroPost(&$mensaje,$id_empresa){
    $rtn=true;
    $mensaje='';
    if(existeDni(trim($_POST['dni']),$id_empresa)){
      $rtn=false;
      $mensaje='<br><strong>DNI ya registrado --> '.$_POST['dni'].'</strong>';
    }  
    return $rtn;
}

function get_fechayhoy_ddmmyyyy_amigable(){
    $mes=str_pad(date('m'), 2, "0", STR_PAD_LEFT); 
    $dia=str_pad(date('d'), 2, "0", STR_PAD_LEFT);
    $hora=str_pad(date('H'), 2, "0", STR_PAD_LEFT);
    $minuto=str_pad(date('i'), 2, "0", STR_PAD_LEFT);
    $segundo=str_pad(date('s'), 2, "0", STR_PAD_LEFT);
    return $dia.'/'.$mes.'/'.date('Y').' - '.$hora.':'.$minuto.':'.$segundo;
}

function get_fechayhoy_ddmmyyyy(){
	$mes=str_pad(date('m'), 2, "0", STR_PAD_LEFT); 
	$dia=str_pad(date('d'), 2, "0", STR_PAD_LEFT);
	$hora=str_pad(date('H'), 2, "0", STR_PAD_LEFT);
	$minuto=str_pad(date('i'), 2, "0", STR_PAD_LEFT);
	$segundo=str_pad(date('s'), 2, "0", STR_PAD_LEFT);
	return date('Y').$mes.$dia.$hora.$minuto.$segundo;
}

function getBodyNotificacion($idafiliadoretorno,$rw_afiliado_padre){
  $rs=get_afiliadobyid($idafiliadoretorno);
  $rw=$rs[0];

  $bodyhtml='<b>Se ha realizado un Alta de Afiliado '.(!esSoloCliente() ? '':'sólo cliente').': </b><br><br>'
   .'Afiliado '.(!esSoloCliente() ? '':'sólo cliente').' Alta:<b>'.$_POST['nombre_apellidos'].'</b><br><br>'
   .'Número:<b>'.$rw['numero'].'</b><br><br>'
   .'DNI:<b>'.$_POST['dni'].'</b><br><br>'
   .'Patrocinador:<b>'.utf8_encode($rw_afiliado_padre['nombre']).'</b><br><br>'  
   .'Número Patrocinador:<b>'.utf8_encode($rw_afiliado_padre['numero']).'</b><br><br>'      
   .'Empresa:<b>'.utf8_encode($rw['empresa']).'</b><br><br>'
   .'Teléfono:<b>'.$_POST['telefono'].'</b><br><br>'
   .'E-mail:<b>'.$_POST['email'].'</b><br><br>'
   .'Dirección:<b>'.$_POST['direccion'].'</b><br><br>'     
   .'Fecha-Hora alta:<b>'.get_fechayhoy_ddmmyyyy_amigable().'</b><br><br>';
   return $bodyhtml;
   
}

function enviar_notificacionAlta($ar_destinatarios,$bodyhtml,&$mensajeRetorno){
    require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->Host = "localhost"; 
    $mail->FromName = !esSoloCliente() ? "Afiliados":"Afiliados sólo Cliente";
    $mail->Subject = !esSoloCliente() ? 'Alta Afiliados': "Alta Afiliados sólo Cliente";
    foreach($ar_destinatarios as $destinatario){
	$mail->AddAddress($destinatario);
    }
    $mail->MsgHTML($bodyhtml);

    if(!$mail->Send()){
       $mensajeRetorno="Error envío email: " . $mail->ErrorInfo;
       return false;
    } else {
       $mensajeRetorno="OK";
       return true;
    }  
}

function get_next_numero_afiliado($idempresa){
  $sql="select if(max(numero) is null, 0, max(numero) ) + 1 numero
  from ".getTablaAfiliados()." 
  where id_empresa=".$idempresa;
  $rs=get_registros($sql);
  return $rs[0]['numero'];
}
function guardar_afiliado(&$mensajeRetorno,&$idafiliadoretorno,&$new_id_md5,$rw_afiliado_padre){

    $idafiliadonew=get_next_idauto_incremento(getTablaAfiliados());
    $idafiliadoretorno=$idafiliadonew;
    $fechayhoy=get_fechayhoy_ddmmyyyy();
    $carpeta=!esSoloCliente() ? "adjuntos_afiliados/":"adjuntos_afiliados_solo_clientes/";

    //adjunto_cert_tit_cta
    $fichero_upload_destino_adjunto_cert_tit_cta = $idafiliadonew."_cert_tit_cta_".$fechayhoy;
    $result_upload_adjunto_cert_tit_cta=subir_fichero_post('adjunto_cert_tit_cta',RUTA_RAIZ_RELATIVA.$carpeta,$fichero_upload_destino_adjunto_cert_tit_cta); 
    if($result_upload_adjunto_cert_tit_cta['estado']=='OK'){
        $fichero_bd_adjunto_cert_tit_cta=$result_upload_adjunto_cert_tit_cta['mensaje'];
    }else{
        $mensajeRetorno=$result_upload_adjunto_cert_tit_cta['mensaje'];
        return false;
    }
    //fin adjunto_cert_tit_cta
  
   global $link;
   $fecha = date("Y-n-j");
   $fecha_para_md5 = date("jnY");
   $new_id_md5=md5($idafiliadonew.'_'.$fecha_para_md5.'_'.$rw_afiliado_padre['id_empresa']);

   $cuenta_bancaria=hasdato($_POST['cuenta_bancaria']) ? "'".utf8_decode($_POST['cuenta_bancaria'])."'" : "NULL";
   $fichero_bd_adjunto_cert_tit_cta=hasdato($fichero_bd_adjunto_cert_tit_cta) ? "'".$fichero_bd_adjunto_cert_tit_cta."'" : "NULL";

   $swift_bic=hasdato($_POST['swift_bic']) ? "'".utf8_decode($_POST['swift_bic'])."'" : "NULL";
 
   $sql = "INSERT INTO ".getTablaAfiliados()." ( 
              id_md5,
              numero,  
              id_afiliado_padre,
              id_empresa,
              fecha_alta,
              nombre,
              dni,
              contrasena,
              direccion,
              telefono,
              email,
              cuenta_bancaria,
              adjunto_cert_tit_cta,
              importe_pagado,
              swift_bic,
              tipo_persona) 
            VALUES ('".$new_id_md5."',
                    NULL,
                    ".$rw_afiliado_padre['id_afiliado'].",
                    ".$rw_afiliado_padre['id_empresa'].", 
                    '".$fecha."',
                    '".utf8_decode($_POST['nombre_apellidos'])."',
                    '".purgar_dni(utf8_decode(trim($_POST['dni'])))."',
                    '".utf8_decode(trim($_POST['contrasena']))."',
                    '".utf8_decode($_POST['direccion'])."',
                    '".$_POST['telefono']."',
                    '".$_POST['email']."',
                    ".$cuenta_bancaria.",
                    ".$fichero_bd_adjunto_cert_tit_cta.",
                    NULL,
                    ".$swift_bic.",
                    ".$_POST['tipo_persona']."
                    ) "; 

   $res=mysqli_query($link,$sql);
   return $res;
}

function subir_fichero_post($namefileinput,$carpeta,$fichero_destino){
 $rt=array();
 $rt['estado']='';
 $rt['mensaje']='';
 $nombre_archivo = $_FILES[$namefileinput]['name'];
 if($nombre_archivo!=''){
    $extension_archivo = $_FILES[$namefileinput]['type'];   
    $extension_archivo=explode('/',$extension_archivo);
    $extension_archivo=$extension_archivo[1];
    
    if(move_uploaded_file($_FILES[$namefileinput]['tmp_name'],
                                 $carpeta.$fichero_destino.'.'.$extension_archivo)){
       $rt['estado']='OK'; 
       $rt['mensaje']=$fichero_destino.'.'.$extension_archivo;
    }else{
       $rt['estado']='KO'; 
       $rt['mensaje']='No se ha podido subir fichero al servidor:'.$fichero_destino.'.'.$extension_archivo;
    }
 }else{
       $rt['estado']='OK'; 
       $rt['mensaje']='';
 }
 return $rt;
}

function purgar_dni($valor){
  $retorno=quitar_extranos($valor);
  $retorno=strtoupper($retorno);
  return $retorno;
}
function quitar_extranos($valor){
  $retorno=trim($valor);
  $retorno=quitar_tildes($retorno);
  $retorno=preg_replace('([^A-Za-z0-9])', '', $retorno);
  $retorno=quitar_blancos($retorno);
  return $retorno;
}


function get_formato_token($token){
	$retorno=trim($token);
	$retorno=quitar_tildes($retorno);
        $retorno=quitar_blancos($retorno);
	$retorno=strtolower($retorno);
	$retorno=str_replace(" ","_",$retorno);
        $retorno=str_replace("__","_",$retorno);
	return $retorno;
}

function quitar_blancos($cadena){
    $ar=explode(" ",$cadena);
    $retorno='';
    foreach($ar as $item){
        $item=trim($item);
        if($item!=''){
             $retorno.=$item." ";
        }
    }
    return trim($retorno);
}

function quitar_tildes($cadena){
   $no_permitidas=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
   $permitidas=array ("a","e","i","o","u","A","E","I","O","U","n","N","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
   $texto = str_replace($no_permitidas, $permitidas ,$cadena);
   $texto = str_replace(",", "" ,$texto);
   return $texto;
}

function get_next_idauto_incremento($tabla){
    $sql = "SHOW TABLE STATUS LIKE '".$tabla."'";
    $result = get_registros($sql);
    return $result[0]['Auto_increment'];
}

function hasdato($valor){
  if(trim($valor)==''){
      return false;
  }
  else{
      return true;  
  }
}

function getTablaAfiliados(){
  $tabla='afiliados';
  if(esSoloCliente()) {
    $tabla='afiliados_solo_clientes';
  }
  return $tabla;
}

function esSoloCliente(){
  if($_POST['solo_cliente']=='on'){
     return true;
  }
  if(isset($_GET['idcliente'])){
     return true;
  }
  return false;
}

?>