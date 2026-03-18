<?php

function get_afiliados_byIdEmpresaTree_8x4($idempresa){
  
  $sql="select 
           a84.id_nodo_padre
          ,a84.id_nodo
          ,a84.id_afiliado
          ,a.numero
          ,a.estado
       from afiliados_8x4 a84 
       left join afiliados_8x4 ap84
       on a84.id_nodo_padre=ap84.id_nodo
       and a84.id_empresa=ap84.id_empresa

       left join afiliados a 
       on a.id_afiliado=a84.id_afiliado
       and a.id_empresa=a84.id_empresa

       where a84.id_empresa=".$idempresa.
       " order by a84.id_nodo_padre, a84.id_nodo";

   return get_registros($sql);
}

function get_afiliados_byIdEmpresaTreeImportes_8x4($idempresa){
  
  $sql="select 
           a84.id_nodo_padre
          ,a84.id_nodo
          ,a84.id_afiliado
          ,a84.nivel
          ,a.numero
          ,a.nombre
          ,a.estado
          ,ifnull(aca.importe,0) total
       from afiliados_8x4 a84 
       left join afiliados_8x4 ap84
       on a84.id_nodo_padre=ap84.id_nodo
       and a84.id_empresa=ap84.id_empresa

       left join afiliados a 
       on a.id_afiliado=a84.id_afiliado
       and a.id_empresa=a84.id_empresa

       left join afiliados ap 
       on ap.id_afiliado=ap84.id_afiliado
       and ap.id_empresa=ap84.id_empresa
       
       left join (select nivel, id_afiliado_diferido, sum(importe) importe 
	               from afiliados_comision_arbol
				      where tipo_arbol=2
				      group by nivel, id_afiliado_diferido
                  having sum(importe) is not null) aca
				  
       on aca.id_afiliado_diferido=a84.id_afiliado
       and aca.nivel=a84.nivel

  
       where a84.id_empresa=".$idempresa.
       " order by a84.id_nodo_padre, a84.id_nodo";

   return get_registros($sql);
}



?>