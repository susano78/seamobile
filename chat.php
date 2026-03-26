<?php
// chat.php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // ajusta el dominio en producción

require 'config.php'; // aquí está tu OPENAI_API_KEY
include('mysql.php');
include('global_controlador.php');
include('global_vista.php');
include('global_modelo.php');
include('iatematicas_modelo.php');
include('afiliados_iatokens_modelos.php');


// 1. Leer el mensaje enviado por POST
$input = file_get_contents('php://input');
$data  = json_decode($input, true);
$mensajeUsuario = $data['mensaje'] ?? '';
$historial = $data['historial'] ?? [];

$themeId = isset($data['theme_id']) && is_numeric($data['theme_id'])
    ? (int)$data['theme_id']
    : null;

conectar();
$rs_iatematica=get_ia_tematica_byId($themeId);
$rw_tematica=$rs_iatematica[0];
$comportamiento=ecd($rw_tematica['comportamiento']);
desconectar();

$perfil = $data['perfil'] ?? [];
$contextoPerfil = [];
if (!empty($perfil['nombre'])) {
    $contextoPerfil[] = "Nombre del usuario: {$perfil['nombre']}";
}
if (!empty($perfil['sexo'])) {
    $contextoPerfil[] = "Sexo: {$perfil['sexo']}";
}
if (!empty($perfil['edad'])) {
    $contextoPerfil[] = "Edad: {$perfil['edad']} años";
}
if (!empty($perfil['pais'])) {
    $contextoPerfil[] = "País: {$perfil['pais']}";
}

$systemContent = 'Respondes siempre en español y de forma clara.';
if(hasdato($comportamiento)){
   $systemContent = $comportamiento;
}

if (!empty($contextoPerfil)) {
    $systemContent .= ' Datos del usuario: ' . implode(', ', $contextoPerfil) . '.';
}

if (trim($mensajeUsuario) === '') {
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

// 2. Preparar la petición a la API de OpenAI
$url = 'https://api.openai.com/v1/chat/completions';

$messages = [];
$messages[] = ['role' => 'system', 'content' => $systemContent];

// Historial previo
if (is_array($historial)) {
    foreach ($historial as $msg) {
        if (
            isset($msg['role'], $msg['content']) &&
            in_array($msg['role'], ['user', 'assistant'])
        ) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content']
            ];
        }
    }
}

// Mensaje actual (seguridad extra)
$messages[] = ['role' => 'user', 'content' => $mensajeUsuario];

$payload = [
    'model' => 'gpt-4.1-mini',
    'messages' => $messages,
];



// 3. Llamar a la API con cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY,
    ],
    CURLOPT_POSTFIELDS     => json_encode($payload),
]);

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['error' => 'Error en cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

// 4. Procesar la respuesta de OpenAI
$dataResponse = json_decode($response, true);

if (!isset($dataResponse['choices'][0]['message']['content'])) {
    echo json_encode(['error' => 'Respuesta inesperada de la API', 'raw' => $dataResponse]);
    exit;
}

$textoIA = $dataResponse['choices'][0]['message']['content'];

// 5. Calcular tokens consumidos (provistos exactos por la API de OpenAI)
$totalTokens = $dataResponse['usage']['total_tokens'] ?? 0;
$saldo_tokens=0;

if ($totalTokens > 0) {
    $id_afiliado = get_idafiliado_login();
    if ($id_afiliado) {
        conectar();
        
        // Comprobar si existe, si no, crear con saldo inicial
        if (!comprobarExisteTokenAfiliado($id_afiliado)) {
            insertarSaldoInicialTokens($id_afiliado);
        }
        
        // Restar los tokens consumidos
        restarTokensAfiliado($id_afiliado, $totalTokens);

        $saldo_tokens=obtenerSaldoTokensAfiliado(get_idafiliado_login());
        
        desconectar();
    }
}

// 6. Devolver al frontend
echo json_encode([
    'respuesta' => $textoIA,
    'saldo_tokens' => $saldo_tokens,
]);
?>