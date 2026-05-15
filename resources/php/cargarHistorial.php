<?php

session_start();

require_once(
    $_SERVER['DOCUMENT_ROOT'] .
    '/cositas/Asesorias/resources/php/conn.php'
);

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

$pagina =
    intval($datos['pagina'] ?? 1);

$limite =
    intval($datos['limite'] ?? 6);

$offset =
    ($pagina - 1) * $limite;

$nombre =
    trim($datos['nombre'] ?? '');

$materia =
    trim($datos['materia'] ?? '');

$fecha =
    trim($datos['fecha'] ?? '');

$obj = new ConexionBD();

$conn = $obj->getConexion();

$id = $_SESSION['ID'];

$rol = $_SESSION['ROL'];

if($rol === 'asesor'){

    $joinPersona = "
        INNER JOIN estudiantes e
            ON s.idEstudiante =
               e.idEstudiante
    ";

    $camposPersona = "
        e.nombre,
        e.apellidos,
        e.carrera
    ";

    $whereRol = "
        s.idAsesor = :id
    ";

    $idReportado = 's.idEstudiante';

}else{

    $joinPersona = "
        INNER JOIN asesores a
            ON s.idAsesor =
               a.idAsesor
    ";

    $camposPersona = "
        a.nombre,
        a.apellidos,
        a.carrera
    ";

    $whereRol = "
        s.idEstudiante = :id
    ";

    $idReportado = 's.idAsesor';
    
}

$whereExtra = "
AND (
    s.estado = 'terminada'
    OR
    s.estado = 'cancelada'
)
";

if($nombre !== ''){

    if($rol === 'asesor'){

        $whereExtra .= "
        AND CONCAT(
            e.nombre,
            ' ',
            e.apellidos
        ) LIKE :nombre
        ";

    }else{

        $whereExtra .= "
        AND CONCAT(
            a.nombre,
            ' ',
            a.apellidos
        ) LIKE :nombre
        ";

    }

}

if($materia !== ''){

    $whereExtra .= "
    AND m.nombre
    LIKE :materia
    ";
}

if($fecha !== ''){

    $whereExtra .= "
    AND DATE(s.fecha) = :fecha
    ";
}

$query = "SELECT

    s.*,

    $camposPersona,

    m.nombre AS materia

FROM sesiones s

$joinPersona

LEFT JOIN materias m
    ON s.idMateria =
       m.idMateria

LEFT JOIN reportes r
ON r.idReportado = $idReportado
AND r.idReportante = :idReportante

WHERE

    $whereRol

    $whereExtra

AND r.idReportado IS NULL

ORDER BY s.fecha DESC

LIMIT :limite

OFFSET :offset

";

$stmt = $conn->prepare($query);

$stmt->bindParam(':id', $id);
$stmt->bindParam(':idReportante', $id);

if($nombre !== ''){

    $nombreLike =
        "%$nombre%";

    $stmt->bindParam(
        ':nombre',
        $nombreLike
    );

}

if($materia !== ''){

    $materiaLike =
        "%$materia%";

    $stmt->bindParam(
        ':materia',
        $materiaLike
    );

}

if($fecha !== ''){

    $stmt->bindParam(
        ':fecha',
        $fecha
    );

}

$stmt->bindParam(
    ':limite',
    $limite,
    PDO::PARAM_INT
);

$stmt->bindParam(
    ':offset',
    $offset,
    PDO::PARAM_INT
);

$stmt->execute();

$historial =
    $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryTotal = "SELECT COUNT(*) total

FROM sesiones s

$joinPersona

LEFT JOIN materias m
    ON s.idMateria =
       m.idMateria

       LEFT JOIN reportes r
ON r.idReportado = $idReportado
AND r.idReportante = :idReportante

WHERE

    $whereRol

    $whereExtra

    AND r.idReportado IS NULL
";

$stmtTotal =
    $conn->prepare($queryTotal);

$stmtTotal->bindParam(':id', $id);
$stmtTotal->bindParam(':idReportante', $id);

if($nombre !== ''){

    $stmtTotal->bindParam(
        ':nombre',
        $nombreLike
    );

}

if($materia !== ''){

    $stmtTotal->bindParam(
        ':materia',
        $materiaLike
    );

}

if($fecha !== ''){

    $stmtTotal->bindParam(
        ':fecha',
        $fecha
    );

}

$stmtTotal->execute();

$total =
    $stmtTotal
    ->fetch(PDO::FETCH_ASSOC)['total'];

$totalPaginas =
    ceil($total / $limite);

echo json_encode([

    'mensaje' => 'OK',

    'historial' => $historial,

    'totalPaginas' => $totalPaginas

]);