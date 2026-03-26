<?php

function comprobarExisteTokenAfiliado($id_afiliado) {
    $ar = [
        'tabla' => 'afiliados_ia_tokens',
        'select#1' => 'id_ia_token',
        'n#id_afiliado' => $id_afiliado
    ];
    $sql = get_sql_select($ar);
    $res = get_registros($sql);
    return count($res) > 0;
}

function obtenerSaldoTokensAfiliado($id_afiliado) {
    $ar = [
        'tabla' => 'afiliados_ia_tokens',
        'select#1' => 'saldo_tokens',
        'n#id_afiliado' => $id_afiliado
    ];
    $sql = get_sql_select($ar);
    $res = get_registros($sql);
    if (count($res) > 0) {
        return (int)$res[0]['saldo_tokens'];
    }
    return 0;
}

function insertarSaldoInicialTokens($id_afiliado) {
    $ar = [
        'tabla' => 'afiliados_ia_tokens',
        'id' => 'new',
        'n#id_afiliado' => $id_afiliado,
        's#fecha_actualizacion' => date('Y-m-d H:i:s'),
        'n#saldo_tokens' => 4000000
    ];
    save_array_bd($ar);
}

function restarTokensAfiliado($id_afiliado, $totalTokens) {
    global $link;
    $id_afiliado = (int)$id_afiliado;
    $totalTokens = (int)$totalTokens;
    $fecha = date('Y-m-d H:i:s');
    
    $sql = "UPDATE afiliados_ia_tokens 
            SET saldo_tokens = saldo_tokens - $totalTokens, 
                fecha_actualizacion = '$fecha' 
            WHERE id_afiliado = $id_afiliado";
            
    return mysqli_query($link, $sql);
}

?>
