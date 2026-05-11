<?php

session_start();

require_once(
    $_SERVER['DOCUMENT_ROOT'] .
    '/cositas/Asesorias/resources/php/conn.php'
);

header('Content-Type: application/json');

if(
    !isset($_SESSION['ID']) ||
    $_SESSION['ROL'] !== 'asesor'
){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'No autorizado, de alguna manera xd'
    ]);

    exit;
}

$datos = json_decode(
    file_get_contents("php://input"),
    true
);

if(
    !isset($datos['idEstudiante']) || !isset($datos['idMateria']) || !isset($datos['fecha'])
){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'Faltan datos'
    ]);

    exit;
}

$idAsesor = $_SESSION['ID'];

$idEstudiante = intval($datos['idEstudiante']);

$idMateria = intval($datos['idMateria']);

$fecha =
    str_replace(
        'T',
        ' ',
        $datos['fecha']
    );

try{

    $obj = new ConexionBD();
    $conn = $obj->getConexion();

    /*
        Verificar que el estudiante exista
    */

    $query = "
    SELECT idEstudiante
    FROM estudiantes
    WHERE idEstudiante = :id
    ";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(
        ':id',
        $idEstudiante
    );

    $stmt->execute();

    // verificacion en si
    if($stmt->rowCount() <= 0){

        echo json_encode([
            'mensaje' => 'error',
            'error' => 'El estudiante no existe'
        ]);

        exit;
    }

    /*
        Insertar sesion
    */

    $query = "
    INSERT INTO sesiones (

        idAsesor,
        idEstudiante,
        idMateria,
        estado,
        fecha

    )

    VALUES (

        :idAsesor,
        :idEstudiante,
        :idMateria,
        'pendiente',
        :fecha

    )
    ";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(
        ':idAsesor',
        $idAsesor
    );

    $stmt->bindParam(
        ':idEstudiante',
        $idEstudiante
    );

    $stmt->bindParam(
        ':idMateria',
        $idMateria
    );

    $stmt->bindParam(
        ':fecha',
        $fecha
    );

    $stmt->execute();

    echo json_encode([
        'mensaje' => 'OK'
    ]);

}catch(Exception $e){

    echo json_encode([
        'mensaje' => 'error',
        'error' => $e->getMessage()
    ]);

}