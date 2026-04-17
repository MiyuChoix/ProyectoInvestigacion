<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();


$mensaje = "error";
$error = 1;


if (is_null($conn)) {
    $error = 100;
    //$mensaje = $obj_conexion->getMensaje();
} else {


    $qry_ingresar = "SELECT idMateria, nombre
    FROM materias";


    $stmt = $conn->prepare($qry_ingresar);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();


    $count = $stmt->rowCount();

    if ($count >= 1) {

        $registro = $stmt->fetchAll();
        $mensaje = $registro;

    } else {
        $error = 101;
    }
}

$datos['mensaje'] = $mensaje;
$datos['error'] = $error;

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos)

?>