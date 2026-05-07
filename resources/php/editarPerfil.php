<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/cositas/Asesorias/resources/php/conn.php');

header('Content-Type: application/json');

if(!isset($_SESSION['ID'])){

    echo json_encode([
        'mensaje' => 'No autenticado'
    ]);

    exit;
}

$datos = json_decode(
    file_get_contents("php://input"),
    true
);

$obj = new ConexionBD();
$conn = $obj->getConexion();

$tabla =
    $_SESSION['ROL'] === 'asesor'
    ? 'asesores'
    : 'estudiantes';

$id_columna =
    $_SESSION['ROL'] === 'asesor'
    ? 'idAsesor'
    : 'idEstudiante';

$query = "
UPDATE $tabla

SET
    nombre = :nombre,
    carrera = :carrera,
    telefono = :telefono,
    instagram = :instagram,
    discord = :discord,
    twitter = :twitter,
    facebook = :facebook

WHERE $id_columna = :id
";

$stmt = $conn->prepare($query);

$stmt->bindParam(':nombre', $datos['nombre']);
$stmt->bindParam(':carrera', $datos['carrera']);
$stmt->bindParam(':telefono', $datos['telefono']);
$stmt->bindParam(':instagram', $datos['instagram']);
$stmt->bindParam(':discord', $datos['discord']);
$stmt->bindParam(':twitter', $datos['twitter']);
$stmt->bindParam(':facebook', $datos['facebook']);

$stmt->bindParam(':id', $_SESSION['ID']);

$stmt->execute();

echo json_encode([
    'mensaje' => 'OK'
]);
?>