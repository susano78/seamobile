<?php

// include('tree_unilevel.php');
// include('table_matriz8x4.php');

function show_inicio(){
    $arvista=array();
    $rs_afiliado= esAfiliadoLoginSoloCliente() ? 
                  get_afiliado_solo_cliente_byId(get_idafiliado_login())
                : get_afiliado_byId(get_idafiliado_login());

    $rw_afiliado=$rs_afiliado[0];

    $rs_empresa=get_datos_empresa(get_idempresa_login());
    $rw_empresa=$rs_empresa[0];

    if (!comprobarExisteTokenAfiliado(get_idafiliado_login())) {
            insertarSaldoInicialTokens(get_idafiliado_login());
    }

    $arvista['saldo_tokens']=obtenerSaldoTokensAfiliado(get_idafiliado_login());
    // $arvista['definicion_calificado']=$rw_empresa['definicion_calificado_empresa'];
    
    $arvista['nombre_usuario']=$rw_afiliado['nombre'];
    $arvista['id_usuario']=$rw_afiliado['id_afiliado'];
    $arvista['id_numero_usuario']=$rw_afiliado['numero'];
    $arvista['sexo']=$rw_afiliado['sexo'];
    $arvista['edad']=$rw_afiliado['edad'];
    $arvista['pais']=$rw_afiliado['pais'];
    $arvista['id_md5_afiliado']=$rw_afiliado['id_md5'];
    // $arvista['estadoSINO']=esAfiliadoLoginSoloCliente() ? 
    //           (sw_estado_afiliado_solo_cliente_activo($rw_afiliado) ? 'SI': 'NO')
    //          :(sw_estado_afiliado_activo($rw_afiliado) ? 'SI': 'NO');

    $rs_iatematicas=get_ia_tematicas_byIdEmpresa($rw_afiliado['id_empresa']);
    $arvista['rs_iatematicas']=$rs_iatematicas;
    $arvista['es_solo_cliente']=esAfiliadoLoginSoloCliente();
    // $arvista['rs_afiliados_tree_unilevel']=get_afiliados_byIdEmpresaTree(get_idempresa_login());
    // $arvista['rs_afiliados_solo_cliente_tree']=get_afiliados_solo_cliente_byIdEmpresaTree(get_idempresa_login());
    // $arvista['rs_afiliados_tree_8x4']=get_afiliados_byIdEmpresaTree_8x4(get_idempresa_login());
    // $arvista['rs_afiliados_treeImportes_8x4']=get_afiliados_byIdEmpresaTreeImportes_8x4(get_idempresa_login());


    //$arvista=resolver_indicadores($arvista);

    include('inicio_vista.php'); 
}

// function resolver_indicadores($arvista){
//     $id_usuario=$arvista['id_usuario'];

//     $arvista['calificadoSINO']='NO';

//     $arvista['n_afiliados_directos_total']=0;
//     $arvista['n_afiliados_directos_activos']=0;
//     $arvista['n_total_unilevel_total']=0;
//     $arvista['n_total_unilevel_activos']=0;

//     $arvista['n_afiliados_directos_solo_cliente_total']=0;
//     $arvista['n_afiliados_directos_solo_cliente_activos']=0;

//     $arvista['n_total_8x4_total']=0;
//     $arvista['n_total_8x4_activos']=0;

//     if(!esAfiliadoLoginSoloCliente()){

//          // solo cliente-----------------------------------------------------
//          $afiliados_solo_cliente_directos = array_filter($arvista['rs_afiliados_solo_cliente_tree'], 
//           function($afiliado_solo_cliente) use ($id_usuario) {
//           return $afiliado_solo_cliente['id_afiliado_padre'] == $id_usuario;
//          });
//          if (!empty($afiliados_solo_cliente_directos)) {
//             $arvista['n_afiliados_directos_solo_cliente_total']=count($afiliados_solo_cliente_directos);
//          }

//          $directos_solo_cliente_activos=0;
//          foreach ($afiliados_solo_cliente_directos as $afiliado_solo_cliente_hijo) {
//           if (sw_estado_afiliado_solo_cliente_activo($afiliado_solo_cliente_hijo)){
//                 $directos_solo_cliente_activos++;
//            }
//          }
//          $arvista['n_afiliados_directos_solo_cliente_activos']=$directos_solo_cliente_activos;
//          //----------------------------------------------------------------

//         $afiliados_directos = array_filter($arvista['rs_afiliados_tree_unilevel'], 
//           function($afiliado) use ($id_usuario) {
//           return $afiliado['id_afiliado_padre'] == $id_usuario;
//         });
//         if (!empty($afiliados_directos)) {
//             $arvista['n_afiliados_directos_total']=count($afiliados_directos);
//         }

//         //Tenga 2 usuarios directos que estén activos.
//         $directos_activos=0;
//         foreach ($afiliados_directos as $afiliado_hijo) {
//           if (sw_estado_afiliado_activo($afiliado_hijo)){
//                 $directos_activos++;
//           }
//         }
//         $arvista['n_afiliados_directos_activos']=$directos_activos;
//         if($directos_activos >= 2 && $arvista['definicion_calificado']==1){
//             $arvista['calificadoSINO']='SI';
//         }

//         if($arvista['definicion_calificado']==2 && $directos_activos >= 2){ 
//            // Tenga 2 usuarios directos que estén activos y calificados..
//           $calificados_hijos=0;         
//           foreach ($afiliados_directos as $afiliado_hijo) {
//             if (sw_estado_afiliado_activo($afiliado_hijo)){
//                 $activos=dame_nafiliados_hijos_activos(
//                   $afiliado_hijo['id_afiliado'],
//                    $arvista['rs_afiliados_tree_unilevel']);
//                 if($activos >= 2){
//                       $calificados_hijos++;
//                 }  
//             }
//           }
//           if($calificados_hijos >= 2){
//             $arvista['calificadoSINO']='SI';
//           }
//         }

//         if (!empty($arvista['rs_afiliados_tree_unilevel'])) {
//             $artotales=array();
//             $artotales['total']=0;
//             $artotales['activos']=0;
//             $artotales=dameTotal_Unilevel($artotales, 
//             $id_usuario, 
//             $arvista['rs_afiliados_tree_unilevel'], 
//             0 , 
//             true);

//             $arvista['n_total_unilevel_total']=$artotales['total'];
//             $arvista['n_total_unilevel_activos']=$artotales['activos'];
//         }

//         if (!empty($arvista['rs_afiliados_tree_8x4'])) {
//             $artotales8x4=array();
//             $artotales8x4['total']=0;
//             $artotales8x4['activos']=0;
//             $artotales8x4=dameTotal_8x4($artotales8x4, 
//             $id_usuario, 
//             $arvista['rs_afiliados_tree_8x4'], 
//             0 , 
//             true);

//             $arvista['n_total_8x4_total']=$artotales8x4['total'];
//             $arvista['n_total_8x4_activos']=$artotales8x4['activos'];
//         }
//     }

//   return $arvista;
// }

// function dame_nafiliados_hijos_activos($idAfiliadoPadre,$afiliadosTree){
//   $directos_activos=0;
//   $afiliados_directos = array_filter($afiliadosTree, 
//     function($afiliado) use ($idAfiliadoPadre) {
//     return $afiliado['id_afiliado_padre'] == $idAfiliadoPadre;
//   }); 
//   foreach ($afiliados_directos as $afiliado_hijo) {
//     if (sw_estado_afiliado_activo($afiliado_hijo)){
//           $directos_activos++;
//       }
//   }
//    return $directos_activos;
// }

// function dameTotal_Unilevel($artotales, $idAfiliadoPadre, $afiliados, $nivel , $esRaiz) {
//    $pintar_hijos= ($esRaiz ? ($nivel + 1) : $nivel) <= 4; //solo se cuenta hasta el nivel 4 en base al afiliado logeado.
//    // Filtrar afiliados que tienen el padre actual
//    $hijos = array_filter($afiliados, function($afiliado) use ($idAfiliadoPadre) {
//        return $afiliado['id_afiliado_padre'] == $idAfiliadoPadre;
//    });
  
//    if (!empty($hijos) && $pintar_hijos) {
//       foreach ($hijos as $hijo) {
//           $total=$artotales['total'];
//           $total++;
//           $artotales['total']=$total;
//           if (sw_estado_afiliado_activo($hijo)){
//             $activos=$artotales['activos'];
//             $activos++;
//             $artotales['activos']=$activos;
//           }
//           // Llamada recursiva para los hijos de este nodo, con esRaiz en false
//           $artotales = dameTotal_Unilevel($artotales, $hijo['id_afiliado'], $afiliados, ($esRaiz ? ($nivel + 2) : ($nivel + 1)), false);         
//       }
//   }
//    return $artotales;
// }

// function dameTotal_8x4($artotales, $idAfiliadoPadre, $afiliados, $nivel , $esRaiz) {
//    $pintar_hijos=($esRaiz ? ($nivel + 1) : $nivel) <= 4; //solo se cuenta hasta el nivel 4 en base al afiliado logeado.

//    //obtener el nodo del afiliado en la matriz 8x4
//    $nodo_afiliado = array_filter($afiliados, function($afiliado) use ($idAfiliadoPadre) {
//        return $afiliado['id_afiliado'] == $idAfiliadoPadre;
//    });

//    if (!empty($nodo_afiliado)) {
//     foreach ($nodo_afiliado as $nodo) {$id_nodo_padre=$nodo['id_nodo'];}

//       // Filtrar afiliados que tienen el padre actual
//       $hijos = array_filter($afiliados, function($afiliado2) use ($id_nodo_padre) {
//           return $afiliado2['id_nodo_padre'] == $id_nodo_padre;
//       });

//       if (!empty($hijos) && $pintar_hijos) {
//           foreach ($hijos as $hijo) {
//               $total=$artotales['total'];
//               $total++;
//               $artotales['total']=$total;
//               if (sw_estado_afiliado_activo($hijo)){
//                 $activos=$artotales['activos'];
//                 $activos++;
//                 $artotales['activos']=$activos;
//               }
//               // Llamada recursiva para los hijos de este nodo, con esRaiz en false
//               $artotales = dameTotal_8x4($artotales, $hijo['id_afiliado'], $afiliados, ($esRaiz ? ($nivel + 2) : ($nivel + 1)), false);         
//           }
//       } 
//   }

//    return $artotales;
// }

?>