<?php


define('HEADER_JSON','Content-Type: application/json');
define('CANTIDAD_REGISTRO_POR_PAGINAS_ESTANDAR','25');

//define('RUTA_RAIZ_RELATIVA','./');  //CON INTRANET
define('RUTA_RAIZ_RELATIVA','../intranet/');  //CON INTRANET2

define('root_adjuntos_noticias_marketing','adjuntos_noticias_marketing/');

define('SPADMIN','super_admin'); //PERFIL
define('SPADMIN_USER','spadmin');
define('SPADMIN_PASS','sp9admin9');

function getValueNumericOrDefault($value,$default_value){
   $rtn=$default_value;
   $sw=$value!=null;
   $sw=isset($value) && $sw;
   $sw=hasdato($value) && $sw;
   $value=str_replace('.','',$value);
   $value=str_replace(',','.',$value);
   $sw=is_numeric($value) && $sw;
   if($sw) $rtn=$value; 
   return $rtn;
}

function iif($condicion,$valor1,$valor2){
    if($condicion){
        return $valor1;
    }
    else{
        return $valor2;  
    }
}



function hasdato($valor){
    if(trim($valor)==''){
        return false;
    }
    else{
        return true;  
    }
}

function get_next_id_tabla($tabla){
        $sql = "SHOW TABLE STATUS LIKE '".$tabla."'";
        $result = get_registros($sql);
        $row = $result[0];
        return $row['Auto_increment'];
}

function get_idempresa_login(){
    return $_SESSION["idempresa_login"];
}

function get_empresa_login(){
    return $_SESSION["empresa"];
}

function get_idusuario_login(){
    return $_SESSION["idusuario_login"];
}

function get_idcliente_login(){
    return $_SESSION["idcliente_login"];
}

function get_idafiliado_login(){
    return $_SESSION["idafiliado_login"];
}

function lanzar_headerExito_Error($url_salida,$mensaje_error,$mensaje_exito){
    if(hasdato($mensaje_error)){
        $url_salida.='&error='.base64_encode(htmlentities($mensaje_error));
    }
    if(hasdato($mensaje_exito)){
        $url_salida.='&success='.base64_encode(htmlentities($mensaje_exito));
    }
    
    header("Location: ".$url_salida);
}

function validar_fichero_seguimiento($namefileinput){ // fixed_05022018
 //$maxMegabytesUpload=10485761; //10Mb
 $maxMegabytesUpload=52428800; //50Mb
 $rt=array();
 $rt['estado']='';
 $rt['mensaje']='';
 if(isset($_FILES[$namefileinput])){
     $archivo=$_FILES[$namefileinput]['name'];
     if($archivo!=''){
           //$ext_archivo=pathinfo($archivo, PATHINFO_EXTENSION);
           $tamano_archivo = $_FILES[$namefileinput]['size'];
//            if(! (
//                     (strtolower($ext_archivo)=="pdf" 
//                   || strtolower($ext_archivo)=="jpeg") 
//                     && ($tamano_archivo < $maxMegabytesUpload)
//                  )
//              ){
             // 17/03/2018: se abre la veda para subir cualquier fichero sin validad la extension
             if(!($tamano_archivo < $maxMegabytesUpload)){
                 $rt['estado']='ERROR';
//                $rt['mensaje']='<p>La extensión (pdf/jpeg) o el tamaño del archivo ('.((string)round((((float)$maxMegabytesUpload)/1024/1024),2)).'MB máx.) no es correcta. ('.$namefileinput.')</p>
//	                        <p>Extensión:'.$ext_archivo.'</p>
//		                <p>Tamaño:'.((string)round((((float)$tamano_archivo)/1024/1024),2)).'MB</p>';
                 $rt['mensaje']='<p>El tamaño del archivo ('.((string)round((((float)$maxMegabytesUpload)/1024/1024),2)).'MB máx.) no es correcta. ('.$namefileinput.')</p>
		                 <p>Tamaño:'.((string)round((((float)$tamano_archivo)/1024/1024),2)).'MB</p>';
               
           }else{
               $rt['estado']='OK';
           }
     }
 }       

 return $rt;
}

function subir_fichero_post($namefileinput,$carpeta,$fichero_destino){
 $rt=array();
 $rt['estado']='';
 $rt['mensaje']='';
 $nombre_archivo = $_FILES[$namefileinput]['name'];
 if($nombre_archivo!=''){
//    $extension_archivo = $_FILES[$namefileinput]['type'];   
//    $extension_archivo=explode('/',$extension_archivo);
//    $extension_archivo=$extension_archivo[1];
//    
//    17/03/2018: fix para obtener la extensión para cualquier fichero
      $extension_archivo=pathinfo($nombre_archivo, PATHINFO_EXTENSION);
    
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

function get_valores_en_linea($ar,$separador){
    $retorno='';
    foreach($ar as $value) {
       if(hasdato($value)){
            $retorno.=$value.$separador;       
       } 
    }
    if(hasdato($retorno)){
             $retorno=substr($retorno,0,strlen($retorno)-strlen($separador)); 
    }
    return $retorno;
}


function get_ruta_logo_empresa(){
  global $URL_LOGOS;  
  $retorno=$URL_LOGOS.'logo.jpg'; //ojo
  $empresa='';
  if(isset($_SESSION["empresa"])){
      if(trim($_SESSION["empresa"])!=''){
            $empresa=trim($_SESSION["empresa"]);
      }
  }
  if($empresa==''){
            if(isset($_GET["empresa"])){
                if(trim($_GET["empresa"])!=''){
                      $empresa=trim($_GET["empresa"]);
                }
            }
  }

  if($empresa!=''){
      $sql = "select * from empresas where empresa='".$empresa."'";
      $result = get_registros($sql);
      $row = $result[0];
      $logo=$row['logo'];
      if(trim($logo)!=''){
            $retorno=$URL_LOGOS.$logo.'?'.time(); 
      }
      
  }
  return $retorno;
  
}

function yyyy_mm_dd_to_dd_mm_yyyy($date,$separador='/'){
    $ar_date = explode("-", $date);
    $dia = str_pad($ar_date[2], 2, "0", STR_PAD_LEFT); 
    $mes = str_pad($ar_date[1], 2, "0", STR_PAD_LEFT); 
    $ano = $ar_date[0];
    return $dia.$separador.$mes.$separador.$ano;
}

function yyyymmdd_sin_separador($date){
    $ar_date = explode("-", $date);
    $dia = str_pad($ar_date[2], 2, "0", STR_PAD_LEFT); 
    $mes = str_pad($ar_date[1], 2, "0", STR_PAD_LEFT); 
    $ano = $ar_date[0];
    return $ano.$mes.$dia;
}


//para mes/año
function yyyy_mm_dd_to_mm_yyyy($date,$separador='/'){
    $ar_date = explode("-", $date);
    $mes = str_pad($ar_date[1], 2, "0", STR_PAD_LEFT); 
    $ano = $ar_date[0];
    return $mes.$separador.$ano;
}

function yyyy_mm_dd_to_dd_mm_yyyy_ifhasdata($date,$separador='/'){
    if(!(hasdato($date))){ return '';}
    return yyyy_mm_dd_to_dd_mm_yyyy($date,$separador);
}

function dd_mm_yyyy_to_yyyy_mm_dd($date){
    $ar_date = explode("-", $date);
    $dia = str_pad($ar_date[0], 2, "0", STR_PAD_LEFT); 
    $mes = str_pad($ar_date[1], 2, "0", STR_PAD_LEFT); 
    $ano = $ar_date[2];
    return $ano.'-'.$mes.'-'.$dia;
}

function get_anio_from_yyyy_mm_dia($date){ //espera fecha en formato yyyy-mm-dd
    $ar_date = explode("-", $date);
    return $ar_date[0];
}
function get_mes_from_yyyy_mm_dia($date){ //espera fecha en formato yyyy-mm-dd
    $ar_date = explode("-", $date);
    return $ar_date[1];
}

function ultimoDiaDelMes($fecha) {
    // Obtener el último día del mes usando "last day of" en strtotime
    $ultimoDia = date("Y-m-t", strtotime($fecha));
    return $ultimoDia;
}

function get_formato_fecha_null_0000_00_00($date,$separador){
    $fv_null=is_null($date);
    $retorno='';        
    $pos=strpos(trim($date), '0000-00-00');
    if($pos===false && !$fv_null){
      $retorno=yyyy_mm_dd_to_dd_mm_yyyy(trim($date,$separador));
    }
    return $retorno;
}

function get_formato_fecha_mes_null_0000_00_00($date,$separador){
    $fv_null=is_null($date);
    $retorno='';        
    $pos=strpos(trim($date), '0000-00-00');
    if($pos===false && !$fv_null){
      $retorno=yyyy_mm_dd_to_mm_yyyy(trim($date,$separador));
    }
    return $retorno;
}

function formato_importe_cantidad($numero) {
       return number_format($numero, 2, ',', '.');
}

function formato_importe_cantidad_solo_puntodecimal($numero) {
    $rtn=number_format($numero, 2,'.',',');
    return str_replace(',','',$rtn);
}

function get_cond_filtros_columnas(){
    $cond='';
    if(isset($_POST['filtros_columnas'])){
       foreach($_POST as $campo => $valor){ 

          if($campo!='hdpagina' && $campo!='busqueda_generica' && $campo!='sentido_ord' && $campo!='campo_ord' && $campo!='filtros_columnas' && $campo!='estado' && trim($valor)!=''){
                 $campo=str_replace('_','.',$campo);
                 $campo=str_replace('-','_',$campo);
                 
                 $campo=str_replace('STR.TO.DATE','STR_TO_DATE',$campo);
                 $campo=str_replace('%Y_%m_%d','%Y-%m-%d',$campo);
                 
                 $pos = strpos($campo, 'fecha');
                 if(!$pos===false) {
                     $pos2 = strpos($valor, '/');
                      if(!$pos2===false) {
                         $ar=explode('/',$valor);
                         $valor=$ar[2].'-'.$ar[1].'-'.$ar[0];
                         if(substr($valor,0,1)=='-'){
                             $valor=substr($valor,1,strlen($valor)-1);
                         }
                      }
                 }               
                 $cond.=" and ((".$campo." like '%".$valor."%') or (".$campo." like '%".utf8_decode($valor)."%')) ";       
              }
          }
    }
    return $cond;
}

function get_cond_filtros_columnas_v2(){  //ignorar fecha_desde y fecha_hasta
    $cond='';

    if(isset($_POST['filtros_columnas'])){
       foreach($_POST as $campo => $valor){ 
         
          if($campo!='fecha_desde' && $campo!='fecha_hasta' && $campo!='hdpagina' && $campo!='busqueda_generica' && $campo!='sentido_ord' && $campo!='campo_ord' && $campo!='filtros_columnas' && $campo!='estado' && trim($valor)!=''){
                 $campo=str_replace('_','.',$campo);
                 $campo=str_replace('-','_',$campo);
                 
                 $campo=str_replace('STR.TO.DATE','STR_TO_DATE',$campo);
                 $campo=str_replace('%Y_%m_%d','%Y-%m-%d',$campo);

                 $pos = strpos($campo, 'fecha');
                 if(!$pos===false || $campo=='fecha') {
                      $pos2 = strpos($valor, '/');
                      if(!$pos2===false) {
                         $ar=explode('/',$valor);
                         $valor=$ar[2].'-'.$ar[1].'-'.$ar[0];
                         if(substr($valor,0,1)=='-'){
                             $valor=substr($valor,1,strlen($valor)-1);
                         }
                      }
                 }               
                 $cond.=" and ((".$campo." like '%".$valor."%') or (".$campo." like '%".utf8_decode($valor)."%')) ";       
              }
          }
    }
    return $cond;
}

function get_cond_estado(){
   $cond_estado="";
   
   if(isset($_POST['estado'])){
        if($_POST['estado']!=0){
                    $cond_estado=" and cdc.idestado=".$_POST['estado'];
            }	
   }
      
   return $cond_estado;  
}

function get_cond_desde_hasta($campodb){
    $cond='';
    if(isset($_POST['fecha_desde'])){
      if(trim($_POST['fecha_desde'])!=''){
            $fecha=dd_mm_yyyy_to_yyyy_mm_dd(trim($_POST['fecha_desde']));
            $cond.=" and ".$campodb." >= STR_TO_DATE('".trim($fecha)."','%Y-%m-%d') ";
      }  
    }
    if(isset($_POST['fecha_hasta'])){
      if(trim($_POST['fecha_hasta'])!=''){
            $fecha=dd_mm_yyyy_to_yyyy_mm_dd(trim($_POST['fecha_hasta']));
            $cond.=" and ".$campodb." <= STR_TO_DATE('".trim($fecha)."','%Y-%m-%d') ";
      }  
    }
    return $cond;
}

function get_orderby_filtros_columnas($orderby_defecto){
    $orderby=$orderby_defecto;
    if(isset($_POST['filtros_columnas'])){
         if(isset($_POST['sentido_ord']) && isset($_POST['campo_ord'])){
              if(trim($_POST['sentido_ord'])!='' && trim($_POST['campo_ord'])!=''){
                  
                 $campo=$_POST['campo_ord'];
                 $campo=str_replace('_','.',$campo);
                 $campo=str_replace('-','_',$campo);
                 
                 $campo=str_replace('STR.TO.DATE','STR_TO_DATE',$campo);
                 $campo=str_replace('%Y_%m_%d','%Y-%m-%d',$campo);
                 $campo=str_replace("%Y-%m-%d","'%Y-%m-%d'",$campo); 
                 
                 $orderby=$campo.' '.$_POST['sentido_ord'];
              }
         }
    }
    if($orderby!=''){ $orderby=' order by '.$orderby;}
    return $orderby;
}

function get_paginas_mostrar($paginas,$n_enlaces,$pagina_actual){
  $arr=array();
  
	$mitad=(int)($n_enlaces/2);
	$primero=$pagina_actual-$mitad;
	if($primero<1 || $paginas<$n_enlaces){$primero=1;}
	for($i=0;$i<$n_enlaces;$i++){
		if($primero<=$paginas){
		    $arr[$primero]=1;
		}
		$primero+=1;
	}

  return $arr;
 }

function enlaces_paginacion($paginas,$n_enlaces,$pagina_actual){
	$enlaces="<div><ul class='pagination pagination-lg'>";
	$paginas_a_mostrar=get_paginas_mostrar($paginas,$n_enlaces,$pagina_actual);
	$sw1=false;
	$sw2=false;
	for ($i=0;$i<$paginas;$i++ ){
		
		if($i==0 && !isset($paginas_a_mostrar[1]) && !$sw1){
		  $enlaces.="<li><a class='paginado2' href='#' data-pag='0'>"."««"."</a></li>";
		  $sw1=true;
		}

		if(isset($paginas_a_mostrar[$i+1])){
			if($pagina_actual!=($i+1)){
			  $enlaces.="<li><a class='paginado2' href='#' data-pag='".$i."'>".($i+1)."</a></li>";
			}else{
			  $enlaces.="<li class='active'><a href='#'>".($i+1)."</a></li>";
			}
		}
		
		if($i==($paginas-1) && !isset($paginas_a_mostrar[$paginas]) && !$sw2){
	$enlaces.="<li><a class='paginado2'  href='#' data-pag='".($paginas-1)."'>"."»»"."</a></li>";
		  $sw2=true; 
		}
		
    }
	$enlaces.="</ul></div>";
	return $enlaces;
 }

function get_resultados_paginados($sql
                                 ,$name_datos
                                 ,$total_registros_x_pagina
                                 ,$pagina_actual
                                 ,$limit=''){
    $retorno=array();
    if(hasdato($limit)){
        $sqllimit=$sql." ".$limit;
        $rst=get_registros($sqllimit); 
    }else{
        $rst=get_registros($sql); 
    }
 
    if($pagina_actual!=0){
      $limitpagina=((int)$pagina_actual)*(int)$total_registros_x_pagina;
    }else{
      $limitpagina=$pagina_actual;  
    }
    $total_registros=count($rst); 
    $sql2= $sql . " limit ". $limitpagina .", ".$total_registros_x_pagina;
    $rs=get_registros($sql2);
    $total_pagina=count($rs); 
    if($total_registros<$total_registros_x_pagina){
      $total_registros_x_pagina=$total_registros;
    }
    $retorno[$name_datos]=$rs;

    $retorno['enlaces_paginacion']='';
    if($total_registros>$total_pagina){
               $paginas_decimales=(float)($total_registros/ $total_registros_x_pagina);
               $paginas=(int)($total_registros/ $total_registros_x_pagina);
               if($paginas_decimales>$paginas){
                       $paginas+=1;
               }

               $retorno['enlaces_paginacion']=enlaces_paginacion($paginas,10,$pagina_actual+1);	
    }
    
    return $retorno;
}

function replaceComillasSimples($texto){
    $texto = str_replace("'","''",$texto);
    return $texto;
}

function replaceEuroPost($texto){
    $texto = str_replace(chr(0xE2).chr(0x82).chr(0xAC),"(euro)",$texto);
    $texto= utf8_decode($texto);
    $texto = str_replace("(euro)",chr(128),$texto);
    return $texto;
}

function get_fechayhoy_ddmmyyyy(){
	$mes=str_pad(date('m'), 2, "0", STR_PAD_LEFT); 
	$dia=str_pad(date('d'), 2, "0", STR_PAD_LEFT);
	$hora=str_pad(date('H'), 2, "0", STR_PAD_LEFT);
	$minuto=str_pad(date('i'), 2, "0", STR_PAD_LEFT);
	$segundo=str_pad(date('s'), 2, "0", STR_PAD_LEFT);
	return date('Y').$mes.$dia.$hora.$minuto.$segundo;
}

function get_fechayhoy_ddmmyyyy_amigable(){
	$mes=str_pad(date('m'), 2, "0", STR_PAD_LEFT); 
	$dia=str_pad(date('d'), 2, "0", STR_PAD_LEFT);
	$hora=str_pad(date('H'), 2, "0", STR_PAD_LEFT);
	$minuto=str_pad(date('i'), 2, "0", STR_PAD_LEFT);
	$segundo=str_pad(date('s'), 2, "0", STR_PAD_LEFT);
	return $dia.'/'.$mes.'/'.date('Y').' - '.$hora.':'.$minuto.':'.$segundo;
}

function validar_email($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

function esUrl($cadena) {
    return filter_var($cadena, FILTER_VALIDATE_URL) !== false;
}


function enviar_email($destinatario,$bodyhtml,$asunto)
{
	require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->Host = "localhost"; 
	$mail->FromName = "Intranet Central de Arbitraje";
	$mail->Subject = $asunto;
	$mail->AddAddress($destinatario);
	$mail->MsgHTML($bodyhtml);

	if(!$mail->Send()){
           return "Error envío email: " . $mail->ErrorInfo;
    } else {
           return "OK";
    }
}

function enviar_email_antiSpam($destinatario,$bodyhtml,$asunto,&$mensajeout) //for marketing mailing
{
	require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->Host = "localhost"; 
	$mail->Subject = $asunto;
	$mail->AddAddress($destinatario);
        $mail->SetFrom($address = 'mailingtest@grupodesoluciones.com'
                      ,$name = $_SESSION["empresa"]
                      ,$auto = 0);
	$mail->MsgHTML($bodyhtml);

	if(!$mail->Send()){
           $mensajeout='Error envío email: ' . $mail->ErrorInfo;
           return false;
        } else {
           $mensajeout='';
           return true;
        }
}

function enviar_email_antiSpam_withfrom($destinatario,$bodyhtml,$asunto,$from,&$mensajeout) //for auto mailing
{
	require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->Host = "localhost"; 
	$mail->Subject = $asunto;
	$mail->AddAddress($destinatario);
        $mail->SetFrom($address = 'mailingtest@grupodesoluciones.com'
                      ,$name = $from
                      ,$auto = 0);
	$mail->MsgHTML($bodyhtml);

	if(!$mail->Send()){
           $mensajeout='Error envío email: ' . $mail->ErrorInfo;
           return false;
        } else {
           $mensajeout='';
           return true;
        }
}

//envio_email_por_smpt_21012019
function enviar_email_by_smtp($param_smtp,$destinatario,$bodyhtml,$asunto,$from,$is_html,$adjuntos,&$mensajeout) //for auto mailing
{
    require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    
    $mail->SMTPSecure = 'tls';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = $param_smtp['smpt_host']; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
    $mail->Username = $param_smtp['smpt_username']; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente. 
    $mail->Password = $param_smtp['smpt_password']; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
    $mail->Port = $param_smtp['smpt_port']; // Puerto de conexión al servidor de envio. 
    
    $mail->Subject = $asunto;
    $mail->AddAddress($destinatario);

    $mail->SetFrom($address = $from 
                  ,$name = $from
                  ,$auto = 0);
    
    if($is_html==1){
       $mail->MsgHTML($bodyhtml); //para enviar en formato html
    }else{ 
       $mail->Body = $bodyhtml; //para respetar los parrafos 
    }
    
    if(!(is_null($adjuntos))){
        $n_adjuntos=count($adjuntos);
        for($i=0;$i<$n_adjuntos;$i++){
             $rw=$adjuntos[$i];
             $mail->AddAttachment($rw['ruta'],$rw['nombre']);
             unset($rw);
        }
    }
 
    if(!$mail->Send()){
       $mensajeout='Error envio email: ' . $mail->ErrorInfo;
       return false;
    } else {
       $mensajeout='';
       return true;
    }
}
//fin_envio_email_por_smpt_21012019


//envio_email_por_smpt_22012022
function enviar_email_by_smtp_v2($param_smtp,$param_varios,$adjuntos,&$mensajeout) //for auto mailing
{
    require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
    $mail = new PHPMailer();
    
    $mail->SMTPSecure = 'tls';
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = $param_smtp['smtp_host']; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
    $mail->Username = $param_smtp['smtp_username']; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente. 
    $mail->Password = $param_smtp['smtp_password']; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
    $mail->Port = $param_smtp['smtp_port']; // Puerto de conexión al servidor de envio. 
    
    $mail->Subject = $param_varios['asunto'];
    $mail->AddAddress($param_varios['destinatario']);
	
	if(isset($param_varios['replyto'])){
	       $mail->AddReplyTo($param_varios['replyto'], (isset($param_varios['from']) ? $param_varios['from'] : '')); 
    }
	
    $mail->SetFrom($address = $param_smtp['smtp_username']
                  ,$name = isset($param_varios['from']) ? $param_varios['from'] : $param_smtp['smtp_username']
                  ,$auto = 0);
    
    if($param_varios['is_html']==1){
       $mail->MsgHTML($param_varios['body']); //para enviar en formato html
    }else{ 
       $mail->Body = $param_varios['body']; //para respetar los parrafos 
    }
    
    if(!(is_null($adjuntos))){
        $n_adjuntos=count($adjuntos);
        for($i=0;$i<$n_adjuntos;$i++){
             $rw=$adjuntos[$i];
             $mail->AddAttachment($rw['ruta'],$rw['nombre']);
             unset($rw);
        }
    }
 
    if(!$mail->Send()){
       $mensajeout='Error envio email v2: ' . $mail->ErrorInfo;
       return false;
    } else {
       $mensajeout='';
       return true;
    }
}
//envio_email_por_smpt_22012022



function enviar_email_multiple($ar_destinatarios,$bodyhtml,$asunto)
{
	require_once(dirname(__FILE__).'/PHPMailer/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->Host = "localhost"; 
	$mail->FromName = "Intranet Central de Arbitraje";
	$mail->Subject = $asunto;
        foreach($ar_destinatarios as $destinatario){
	    $mail->AddAddress($destinatario);
        }
	$mail->MsgHTML($bodyhtml);

	if(!$mail->Send()){
           return "Error envío email: ".$mail->ErrorInfo;
    } else {
           return "Correo enviado correctamente";
    }
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


function rsconf_to_array($rs){ //obtiene array con nombre y tipo de campo a partir del json                            //definido en el campo
  $ar=array();
  $n=count($rs);
 
  for($i=0;$i<$n;$i++){
      $typefield='s';
      $rw=$rs[$i];
      $confjson=$rw['config_json'];
      if(hasdato($confjson)){
            $arconf=json_decode(utf8_encode($confjson),true);
            $arconf=$arconf['config'];
            if($arconf['tipo']!=''){
                $typefield=$arconf['tipo'];
            }
            
      }
      $ar[$rs[$i]['campo']]=$typefield;
      unset($rw);
  }
  return $ar;
}

function get_valor_config_byfield($rs,$field,$fieldjson){
  $valor='';  
  $n=count($rs);
  for($i=0;$i<$n;$i++){
      $rw=$rs[$i];
      $campo=$rw['campo'];
      if($campo==$field){
            $confjson=$rw['config_json'];
            if(hasdato($confjson)){
                  $arconf=json_decode(utf8_encode($confjson),true);
                  $arconf=$arconf['config'];
                  if($arconf[$fieldjson]!=''){
                      return $arconf[$fieldjson];
                  }

            }
      }
      unset($rw);
  }
    return $valor;
}

function get_valor_config_byfield_withDefault($rs,$field,$fieldjson,$default){
  $valor=$default;  
  $n=count($rs);
  for($i=0;$i<$n;$i++){
      $rw=$rs[$i];
      $campo=$rw['campo'];
      if($campo==$field){
            $confjson=$rw['config_json'];
            if(hasdato($confjson)){
                  $arconf=json_decode(utf8_encode($confjson),true);
                  $arconf=$arconf['config'];
                  if($arconf[$fieldjson]!=''){
                      return $arconf[$fieldjson];
                  }

            }
      }
      unset($rw);
  }
    return $valor;
}

function get_idautomailing_ByOwnerSeg_OR_CRM($row){
    if(hasdato($row['idconfig_mailing'])){
            if(isset($_SESSION["idusuario_login"])){
              if(hasdato($_SESSION["idusuario_login"])){
                     if($row['idusuario_config_mailing']==$_SESSION["idusuario_login"]
                        || $_SESSION["aut"]=='adm'){
                          return $row['idconfig_mailing'];
                     }
              }
            }
       }
    return 0;                 
}

function getParamGETModuloid() {
    return $_GET['moduloid'];
}


function soy_usuario_afiliado(){  
    if($_SESSION["aut"] == 'afiliado'){
         return true;
    }
    return false;
  }

//fix 08122018 seg_to_interno 
function soy_usuario_clidecli(){  
  if($_SESSION["aut"] == 'clidecli'){
       return true;
  }
  return false;
}

function soy_usuario_cliente(){
  if($_SESSION["aut"] == 'cliente'){
       return true;
  }
  return false;
}
//fin fix 08122018 seg_to_interno 

//fix_abogado_can_set_clideclis 21022019
function soy_usuario_abogado(){
  if($_SESSION["aut"] == 'abogado'){
       return true;
  }
  return false;
}
//fin fix_abogado_can_set_clideclis 21022019

//embudos_abril2019
function soy_usuario_adm(){
  if($_SESSION["aut"] == 'adm'){
       return true;
  }
  return false;
}

function startsWith($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
}

//fin embudos_abril2019

function get_client_ip(){
$ipaddress = '';
if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
else if(isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
else
    $ipaddress = 'UNKNOWN';
return $ipaddress;
} 


function download_file_pdf($pathfileInput,$fileOutput){
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="'.$fileOutput.'"');
    header('Cache-Control: max-age=0');
    readfile($pathfileInput);  
}

function download_file_xml($pathfileInput,$fileOutput){
    header("Content-Type: text/xml");
    header("Content-Length: ".filesize($pathfileInput)); 
    header('Content-Disposition: attachment;filename="'.$fileOutput.'"');
    ob_clean();
    flush();
    readfile($pathfileInput);  
}


function download_file_text_plain($pathfileInput,$fileOutput){
    header("Content-Type: text/plain");
    header("Content-Length: ".filesize($pathfileInput)); 
    header('Content-Disposition: attachment;filename="'.$fileOutput.'"');
    ob_clean();
    flush();
    readfile($pathfileInput);  
}

function download_html_to_excel($html,$fileOutput){
	//header('Content-type: application/excel');
	header("Content-type: application/vnd.ms-excel");
	header('Content-Disposition: attachment;filename="'.$fileOutput.'"');
	echo $html;
}

function get_arMeses(){
    $ar=array();
    $ar["Enero"]=1;
    $ar["Febrero"]=2;
    $ar["Marzo"]=3;
    $ar["Abril"]=4;
    $ar["Mayo"]=5;
    $ar["Junio"]=6;
    $ar["Julio"]=7;
    $ar["Agosto"]=8;
    $ar["Septiembre"]=9;
    $ar["Octubre"]=10;
    $ar["Noviembre"]=11;
    $ar["Diciembre"]=12;
    $ar["Extra"]=13;
    return $ar;
}

function get_arMesesPorNumero(){
    $ar=array();
    $arMeses=get_arMeses();
    foreach($arMeses as $mes=>$valor){
       $ar[$valor]=$mes;
    }
    return $ar;
}


function userActualTienePermisoEnModulo($modulo){
    foreach($_SESSION["modulos_usuario"] as $modulo_item){
        foreach($modulo_item as $modulo_param => $modulo_name){ 
                  if($modulo==$modulo_param) return true;
            }
        }
     return false;   
}


?>