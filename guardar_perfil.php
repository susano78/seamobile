<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // ajusta el dominio en producción

include('mysql.php');
include('global_controlador.php');
include('global_vista.php');
include('global_modelo.php');
include('afiliados_modelo.php');
include('afiliados_solo_clientes_modelo.php');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode([
    'ok' => false,
    'mensaje' => 'Método no permitido'
  ]);
  exit;
}

// Leer JSON recibido
$input = json_decode(file_get_contents('php://input'), true);

// Validación básica
$nombre = trim($input['nombre'] ?? '');
$sexo   = trim($input['sexo'] ?? '');
$edad   = trim($input['edad'] ?? '');
$pais   = trim($input['pais'] ?? '');

$sexo = $sexo === '' ? 'null' : $sexo;
$edad = $edad === '' ? 'null' : $edad;
$pais = $pais === '' ? 'null' : $pais;

// if ($nombre === '' || $sexo === '' || $edad <= 0 || $pais === '') {
//   echo json_encode([
//     'ok' => false,
//     'mensaje' => 'Todos los campos son obligatorios'
//   ]);
//   exit;
// }

if ($nombre === '') {
  echo json_encode([
    'ok' => false,
    'mensaje' => 'El Nombre es obligatorio'
  ]);
  exit;
}

// Validaciones mínimas extra
if  (($sexo!=='null') && (!in_array($sexo, ['M','F']))) {
  echo json_encode([
    'ok' => false,
    'mensaje' => 'Sexo no válido'
  ]);
  exit;
}

if (($edad!=='null') && ($edad < 1 || $edad > 120)) {
  echo json_encode([
    'ok' => false,
    'mensaje' => 'Edad no válida'
  ]);
  exit;
}


conectar();

$param=array();
$param['id_afiliado']=get_idafiliado_login();
$param['nombre']=dcd($nombre); 
$param['sexo']=$sexo; 
$param['edad']=$edad; 
$param['pais']=dcd($pais); 
$result=esAfiliadoLoginSoloCliente() ? 
    set_afiliado_solo_cliente_perfil_felizia_bbdd($param)
  : set_afiliado_perfil_felizia_bbdd($param); 

desconectar();

if($result){
    echo json_encode([
    'ok' => true,
    'mensaje' => 'Perfil actualizado correctamente'
    ]);
    exit;
}

echo json_encode([
    'ok' => false,
    'mensaje' => 'Error de base de datos al guardar el perfil'
    ]);
exit;
