<?php

function show_table_matriz8x4($rs_datos, $id_afiliado){
   $htmltable = generarTableHTML($id_afiliado, $rs_datos);
   echo hasdato($htmltable) ? $htmltable : "No tiene afiliados en Matriz 8x4";
}

function generarTableHTML($id_afiliado, $afiliados_8x4)
{
    if (empty($afiliados_8x4)) {
        return '';
    }

    // 1️⃣ Indexar datos
    $porNodo = [];
    $hijosPorPadre = [];

    foreach ($afiliados_8x4 as $item) {
        $porNodo[$item['id_nodo']] = $item;
        $hijosPorPadre[$item['id_nodo_padre']][] = $item;
    }

    // 2️⃣ Obtener nodo raíz del afiliado
    $id_nodo_raiz = null;
    foreach ($afiliados_8x4 as $item) {
        if ($item['id_afiliado'] == $id_afiliado) {
            $id_nodo_raiz = $item['id_nodo'];
            break;
        }
    }

    if ($id_nodo_raiz === null) {
        return '';
    }

    // 3️⃣ Recorrer descendencia (BFS)
    $descendencia = [];
    $cola = [$id_nodo_raiz];

    while (!empty($cola)) {
        $padre = array_shift($cola);

        if (!empty($hijosPorPadre[$padre])) {
            foreach ($hijosPorPadre[$padre] as $hijo) {
                $descendencia[] = $hijo;
                $cola[] = $hijo['id_nodo'];
            }
        }
    }

    if (empty($descendencia)) {
        return '';
    }

    // 4️⃣ Agrupar por nivel
    $agrupado = [];

    foreach ($descendencia as $item) {
        $nivel = $item['nivel'];

        if (!isset($agrupado[$nivel])) {
            $agrupado[$nivel] = [
                'numeros' => [],
                'total' => 0
            ];
        }

        $agrupado[$nivel]['numeros'][] = $item['numero'];
        $agrupado[$nivel]['total'] += (float)$item['total'];
    }

    ksort($agrupado);

    // 5️⃣ Construir HTML
$html = '
<style>
.tabla-grid {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.tabla-grid th,
.tabla-grid td {
    border: 1px solid #dcdcdc;
    padding: 8px 10px;
}

.tabla-grid thead {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #ffffff;
    font-weight: 600;
}

.tabla-grid th {
    text-align: center;
}

.col-nivel {
    text-align: center;
    font-weight: 600;
    width: 90px;
}

.col-numeros {
    text-align: left;
}

.col-total {
    text-align: right;
    font-weight: 600;
    width: 120px;
}

.tabla-grid tbody tr:nth-child(even) {
    background-color: #fafafa;
}
</style>
<br>
<div style="overflow-x:auto;">
<table class="tabla-grid">
<thead>
<tr>
    <th>Niveles</th>
    <th>Nº Afiliados</th>
    <th>Total €</th>
</tr>
</thead>
<tbody>
';

$nivel_relativo=0;
foreach ($agrupado as $nivel => $datos) {
   $nivel_relativo++;
    sort($datos['numeros'], SORT_NUMERIC);

    $numeros = implode(', ', $datos['numeros']);
    $total = number_format($datos['total'], 2, ',', '.');

    $html .= '
    <tr>
        <td class="col-nivel">'.$nivel_relativo.'</td>
        <td class="col-numeros">'.$numeros.'</td>
        <td class="col-total">'.$total.' €</td>
    </tr>
    ';
}

$html .= '
</tbody>
</table>
</div>
';

 return $html;
}

?>