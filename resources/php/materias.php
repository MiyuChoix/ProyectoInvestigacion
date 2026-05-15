<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();


$mensaje = "error";
$error = 1;


if (is_null($conn)) {
    $error = 100;
    //$mensaje = $obj_conexion->getMensaje();
} else {

    $pagina =
    intval($_GET['pagina'] ?? 1);

$limite =
    intval($_GET['limite'] ?? 6);

$offset =
    ($pagina - 1) * $limite;

    $qry_ingresar = "SELECT idMateria, nombre
                    FROM materias
                    ORDER BY nombre ASC
                    LIMIT :limite
                    OFFSET :offset";


    $stmt = $conn->prepare($qry_ingresar);
    $stmt->bindParam(':limite',$limite,PDO::PARAM_INT);
    $stmt->bindParam(':offset',$offset,PDO::PARAM_INT);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();


    $count = $stmt->rowCount();

    if ($count < 1) {
        $error = 101;
    } else {
        $materias =
        $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalQuery = "SELECT COUNT(*) total
                        FROM materias";
        $stmtTotal =
        $conn->prepare($totalQuery);

        $stmtTotal->execute();

        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPaginas = ceil($total / $limite);

        $mensaje = "OK";
        $error = 0;
    }
}

$datos['mensaje'] = $mensaje;
$datos['materias'] = $materias;
$datos['totalPaginas'] = $totalPaginas;
$datos['error'] = $error;

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos);
?>