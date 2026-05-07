<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/cositas/Asesorias/resources/php/conn.php');

header('Content-Type: application/json; charset=utf-8');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();

$mensaje = 'error';
$error = 1;

$datos_request = json_decode(file_get_contents('php://input'), true);

if (
    !isset($datos_request['rol']) ||
    !isset($datos_request['id'])
) {

    echo json_encode([
        'mensaje' => $mensaje,
        'error' => 2
    ]);

    exit;
}

$rol = strtolower(trim($datos_request['rol']));
$id = intval($datos_request['id']);

$roles = [
    'asesor' => [
        'tabla' => 'asesores',
        'id' => 'idAsesor'
    ],

    'estudiante' => [
        'tabla' => 'estudiantes',
        'id' => 'idEstudiante'
    ]
];

if (!isset($roles[$rol])) {

    echo json_encode([
        'mensaje' => $mensaje,
        'error' => 105
    ]);

    exit;
}

$tabla = $roles[$rol]['tabla'];
$columna_id = $roles[$rol]['id'];

$query = "
    SELECT *,
           $columna_id AS id
    FROM $tabla
    WHERE $columna_id = :id
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();

$info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$info) {

    echo json_encode([
        'mensaje' => $mensaje,
        'error' => 101
    ]);

    exit;
}

$materias = [];

if ($rol === 'asesor') {

    $query_materias = "
        SELECT m.nombre
        FROM materias m

        INNER JOIN asesor_materias am
            ON m.idMateria = am.idMateria

        WHERE am.idAsesor = :id
    ";

    $stmt_materias = $conn->prepare($query_materias);
    $stmt_materias->bindParam(':id', $id);
    $stmt_materias->execute();

    $materias = $stmt_materias->fetchAll(PDO::FETCH_ASSOC);
}

$propio = false;

if (
    isset($_SESSION['ID']) &&
    $_SESSION['ID'] == $id
) {
    $propio = true;
}

$mensaje = 'OK';
$error = 0;


echo json_encode([
    'mensaje' => $mensaje,
    'error' => $error,
    'rol' => $rol,
    'propio' => $propio,
    'info' => $info,
    'materias' => $materias
]);