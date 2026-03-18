<?php
function get_ia_tematicas_byIdEmpresa($idempresa){
    $sql="select 
             id
            ,id_empresa
            ,nombre
            ,comportamiento
        from ia_tematicas 
        where id_empresa=".$idempresa;  
      return get_registros($sql);
}

function get_ia_tematica_byId($idtematica){
    $sql="select 
             id
            ,id_empresa
            ,nombre
            ,comportamiento
        from ia_tematicas 
        where id=".$idtematica;  
      return get_registros($sql);
}


?>
