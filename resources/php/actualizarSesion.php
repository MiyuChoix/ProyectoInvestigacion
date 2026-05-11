<?php

session_start();

require_once(
    $_SERVER['DOCUMENT_ROOT'] .
    '/cositas/Asesorias/resources/php/conn.php'
);

header('Content-Type: application/json');

if(
    !isset($_SESSION['ID']) ||
    !isset($_SESSION['ROL'])
){

    echo json_encode([
        'mensaje' => 'No autenticado'
    ]);

    exit;
}

$datos = json_decode(
    file_get_contents('php://input'),
    true
);

$idSolicitud =
    intval($datos['idSolicitud']);

$estado =
    trim($datos['estado']);

$permitidos = [
    'aceptada',
    'cancelada',
    'terminada'
];

if(!in_array($estado, $permitidos)){

    echo json_encode([
        'mensaje' => 'Estado invalido'
    ]);

    exit;
}

$obj = new ConexionBD();
$conn = $obj->getConexion();

$idUsuario =
    $_SESSION['ID'];

$rol =
    $_SESSION['ROL'];

/*
-----------------------------------
REGLAS
-----------------------------------

ESTUDIANTE:
- aceptada
- cancelada

ASESOR:
- cancelada
- terminada
*/

if($rol === 'estudiante'){

    if(
        $estado !== 'aceptada' &&
        $estado !== 'cancelada'
    ){

        echo json_encode([
            'mensaje' => 'Accion invalida'
        ]);

        exit;
    }

    $query = "

        UPDATE sesiones

        SET estado = :estado

        WHERE
            idSolicitud = :idSolicitud
        AND
            idEstudiante = :idEstudiante

    ";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(
        ':idEstudiante',
        $idUsuario
    );

}else if($rol === 'asesor'){

    if(
        $estado !== 'cancelada' &&
        $estado !== 'terminada'
    ){

        echo json_encode([
            'mensaje' => 'Accion invalida'
        ]);

        exit;
    }

    $query = "

        UPDATE sesiones

        SET estado = :estado

        WHERE
            idSolicitud = :idSolicitud
        AND
            idAsesor = :idAsesor

    ";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(
        ':idAsesor',
        $idUsuario
    );

}else{

    echo json_encode([
        'mensaje' => 'Rol invalido'
    ]);

    exit;
}

$stmt->bindParam(
    ':estado',
    $estado
);

$stmt->bindParam(
    ':idSolicitud',
    $idSolicitud
);

$stmt->execute();

echo json_encode([
    'mensaje' => 'OK'
]);
?>