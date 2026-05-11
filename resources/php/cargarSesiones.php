<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] .
'/cositas/Asesorias/resources/php/conn.php');

header('Content-Type: application/json');

if(
    !isset($_SESSION['ID']) ||
    $_SESSION['ROL'] !== 'asesor'
){
    echo json_encode([
        'mensaje' => 'error'
    ]);

    exit;
}

$obj = new ConexionBD();
$conn = $obj->getConexion();

$query = "
SELECT
    s.idSolicitud,
    s.estado,
    s.fecha,
    e.idEstudiante,
    e.nombre,
    e.apellidos,
    e.carrera,
    m.nombre AS materia

FROM sesiones s

INNER JOIN estudiantes e
ON s.idEstudiante = e.idEstudiante

LEFT JOIN materias m
ON s.idMateria = m.idMateria

WHERE s.idAsesor = :idAsesor

ORDER BY s.fecha DESC

LIMIT 8
";

$stmt = $conn->prepare($query);

$stmt->bindParam(
    ':idAsesor',
    $_SESSION['ID']
);

$stmt->execute();

$sesiones =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'mensaje' => 'OK',
    'sesiones' => $sesiones
]);