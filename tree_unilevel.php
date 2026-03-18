<?php

function show_tree_unilevel($rs_datos, $id_afiliado){
   $htmltree = generarArbolHTML($id_afiliado, $rs_datos, 0 ,true);
   echo hasdato($htmltree) ? $htmltree : "No tiene afiliados";
}

function generarArbolHTML($idAfiliadoPadre, $afiliados, $nivel , $esRaiz) {
   $html = '';

   $pintar_hijos= ($esRaiz ? ($nivel + 1) : $nivel) <= 4; //solo se muestra hasta el nivel 4 en base al afiliado logeado.
   // Filtrar afiliados que tienen el padre actual
   $hijos = array_filter($afiliados, function($afiliado) use ($idAfiliadoPadre) {
       return $afiliado['id_afiliado_padre'] == $idAfiliadoPadre;
   });

   if (!empty($hijos) && $pintar_hijos) {
      if($esRaiz){ 
         $html .= str_repeat("\t", $nivel) . "<div class='dd treeview' id='treeAfiliados'>\n";
      }

      $html .=  str_repeat("\t", $nivel + 1) ."<ol class='dd-list'>\n";
      foreach ($hijos as $hijo) {
          
          $color=get_color_estado_afiliado($hijo);
          $estado=get_nombre_estado_afiliado($hijo);
          $estado=str_replace("<strong>","<strong class='".$color."'>", $estado);

          $html .= str_repeat("\t", $nivel + 1) . "<li class='dd-item' data-id='".$hijo['id_afiliado']."'>";
          $nombreNodo=(hasdato($hijo['numero']) ? utf8_encode("<strong>".$hijo['numero']." - ".$hijo['nombre']."</strong>") : utf8_encode("<strong>".$hijo['nombre']."</strong>"));
          $nombreNodo.=" (".$estado." - Nivel ".($esRaiz ? ($nivel + 1) : $nivel).")";
          $html .= "<div class='dd-handle'>$nombreNodo</div>\n";
          
          // Llamada recursiva para los hijos de este nodo, con esRaiz en false
          $html .= generarArbolHTML($hijo['id_afiliado'], $afiliados, ($esRaiz ? ($nivel + 2) : ($nivel + 1)), false);
          $html .= str_repeat("\t",  $nivel + 1) . "</li>\n";
      }
      $html .= str_repeat("\t", $nivel + 1) . "</ol>\n";

      if($esRaiz){ 
          $html .= str_repeat("\t", $nivel) . "</div>\n";
      }
  }

   return $html;
}

?>