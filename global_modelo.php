<?php

 function delete_array_bd($ar) {
    global $link;

    $sql = get_sql_delete($ar);
    // echo '<br>' . $sql; return false;

    $result = mysqli_query($link, $sql);

    return $result;
}

 function get_sql_delete($ar){  //valido para sentencias de delete basicas
        $tabla=$ar['tabla'];
        unset($ar['tabla']);
        $campos_valores='';
        foreach($ar as $tipocampo => $valor){
            $ar2=explode("#",$tipocampo);
            $tipo=$ar2[0];
                  $campo=$ar2[1];
                  
                  $campo_valor=$campo."=".$valor; 
                  if($valor!='null' && $valor!='not null'){
                     if($tipo=='s'){
                        $campo_valor=$campo."='".$valor."'";   
                     } 		   
                  }else{
                     $campo_valor=$campo." is ".$valor; 
                  }
                  $campos_valores.=$campo_valor." and ";
         }
         $campos_valores=substr($campos_valores,0,strlen($campos_valores)-strlen(" and "));
         $sql="delete from ".$tabla." where 1=1 and ".$campos_valores;
         return $sql;
 }


 function select_array_bd($ar){ 
    $sql=get_sql_select($ar);
    //echo '<br>'.$sql;
    return get_registros($sql);     
 }
 
  function get_sql_select($ar){  //valido para sentencias de select basicas
    $tabla=$ar['tabla'];
    unset($ar['tabla']);
	 $campos_valores='';
    $campos_select='*';
	 foreach($ar as $tipocampo => $valor){
	    $ar2=explode("#",$tipocampo);
	    $tipo=$ar2[0];
            $campo=$ar2[1];
            
            if($tipo!='select'){
                    $campo_valor=$campo."=".$valor; 
                    if($valor!='null' && $valor!='not null'){
                       if($tipo=='s'){
                            $campo_valor=$campo."='".$valor."'";   
                       } 		   
                    }else{
                        $campo_valor=$campo." is ".$valor; 
                    }
                    $campos_valores.=$campo_valor." and ";
            }else{
               $campos_select=$valor; 
            }
	}
	$campos_valores=substr($campos_valores,0,strlen($campos_valores)-strlen(" and "));
   $sql="select ".$campos_select." from ".$tabla." where 1=1 and ".$campos_valores;
	return $sql;
 }


/////////////////////////////////////

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

 
 function save_array_bd($ar){
    global $link;
    if($ar['id']=='new'){
	   $sql=get_sql_insert($ar);
	}else{
	   $sql=get_sql_update($ar);
	}
   //echo '<br>'.$sql; exit();return false;
   //logger::log($sql);
   $result = mysqli_query($link, $sql);
   return $result;     
 }

 function save_array_bd_log($ar){ //Sólo se usa en modo depuración. No altera dato en bbdd
    global $link;
    if($ar['id']=='new'){
	   $sql=get_sql_insert($ar);
	}else{
	   $sql=get_sql_update($ar);
	}
   //logger::log($sql);
    return true;    
 }
 
 function get_sql_insert($ar){  //valido para sentencias insert básicas
   $tabla=$ar['tabla'];
	unset($ar['tabla']);
	unset($ar['campopk']);
	unset($ar['id']);
	$campo='';
	$valores='';
	foreach($ar as $tipocampo => $valor){
	    $ar2=explode("#",$tipocampo);
	    $tipo=$ar2[0];
            $campo=$ar2[1];	
	    $campos.=$campo.",";
	     if($valor!='null'){
                    if($tipo=='s'){
                               $valor="'".$valor."'";
                    }
		}
        $valores.=$valor.",";
		
	}
   $campos=substr($campos,0,strlen($campos)-1);
	$valores=substr($valores,0,strlen($valores)-1); 
	$sql="insert into $tabla (".$campos.") values (".$valores.")";
	return $sql;
 }
  
 function get_sql_update($ar){  //valido para sentencias de update b�sicas
   $tabla=$ar['tabla'];
	$campopk=$ar['campopk'];
	$id=$ar['id'];
	unset($ar['tabla']);
	unset($ar['campopk']);
	unset($ar['id']);
	$campos_valores='';
	foreach($ar as $tipocampo => $valor){
	    $ar2=explode("#",$tipocampo);
	    $tipo=$ar2[0];
            $campo=$ar2[1];
            $campo_valor=$campo."=".$valor; 
		if($valor!='null'){
		   if($tipo=='s'){
	            $campo_valor=$campo."='".$valor."'";   
                    } 		   
		}
        $campos_valores.=$campo_valor.",";		
	}
	$campos_valores=substr($campos_valores,0,strlen($campos_valores)-1);
   $sql="update $tabla set ".$campos_valores." where $campopk=".$id;
	return $sql;
 }
 
 
function get_next_idauto_incremento($tabla){
    $sql = "SHOW TABLE STATUS LIKE '".$tabla."'";
    $result = get_registros($sql);
    return $result[0]['Auto_increment'];
}

 function execute_sencente_sql($sql){
   global $link;
   $result = mysqli_query($link, $sql);
   return $result;     
 }
 


 
?>
