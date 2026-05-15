<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] .
'/cositas/Asesorias/resources/php/conn.php');

header('Content-Type: application/json');

if(!isset($_SESSION['ID'])){

    echo json_encode([
        'mensaje' => 'No autenticado'
    ]);

    exit;
}

$datos = json_decode(
    file_get_contents('php://input'),
    true
);

$obj = new ConexionBD();
$conn = $obj->getConexion();

$query = "

INSERT INTO reportes (

    idReportante,
    rolReportante,

    idReportado,
    rolReportado,

    motivo,
    descripcion

)

VALUES (

    :idReportante,
    :rolReportante,

    :idReportado,
    :rolReportado,

    :motivo,
    :descripcion

)

";

$stmt = $conn->prepare($query);

$stmt->bindParam(
    ':idReportante',
    $_SESSION['ID']
);

$stmt->bindParam(
    ':rolReportante',
    $_SESSION['ROL']
);

$stmt->bindParam(
    ':idReportado',
    $datos['idReportado']
);

$stmt->bindParam(
    ':rolReportado',
    $datos['rolReportado']
);

$stmt->bindParam(
    ':motivo',
    $datos['motivo']
);

$stmt->bindParam(
    ':descripcion',
    $datos['descripcion']
);

$stmt->execute();

echo json_encode([
    'mensaje' => 'OK'
]);
?>