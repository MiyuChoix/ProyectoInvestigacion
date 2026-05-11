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

$query = "SELECT
            m.idMateria,
            m.nombre
        FROM materias m
        INNER JOIN asesor_materias am
            ON m.idMateria = am.idMateria
        WHERE am.idAsesor = :id
";

$stmt = $conn->prepare($query);

$stmt->bindParam(':id',$_SESSION['ID']);

$stmt->execute();

$materias =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'mensaje' => 'OK',
    'materias' => $materias
]);