<?php

session_start();

require_once('conn.php');

header('Content-Type: application/json');

if(
    !isset($_SESSION['ID']) ||
    $_SESSION['ROL'] !== 'estudiante'
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
    s.fecha,
    a.nombre,
    a.apellidos,
    m.nombre AS materia

FROM sesiones s

INNER JOIN asesores a
ON s.idAsesor = a.idAsesor

INNER JOIN materias m
ON s.idMateria = m.idMateria

WHERE
    s.idEstudiante = :idEstudiante
    AND s.estado = 'pendiente'

ORDER BY s.fecha DESC

";

$stmt = $conn->prepare($query);

$stmt->bindParam(
    ':idEstudiante',
    $_SESSION['ID']
);

$stmt->execute();

$sesiones =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'mensaje' => 'OK',
    'sesiones' => $sesiones
]);