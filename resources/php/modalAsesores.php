<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();


$mensaje = "error";
$error = 1;


$datos_request = json_decode(file_get_contents("php://input"), true);


if (isset($datos_request['idMateria'])) {

    $idMateria = $datos_request['idMateria'];

    if (is_null($conn)) {
        $error = 100;
        //$mensaje = $obj_conexion->getMensaje();
    } else {


        $qry_ingresar = "SELECT a.*
FROM asesores a
INNER JOIN asesor_materias am ON a.idAsesor = am.idAsesor
WHERE am.idMateria = :idMateria";


        $stmt = $conn->prepare($qry_ingresar);
        // se le asigna un valor a la variable :idMateria, dentro del query
        $stmt->bindParam(':idMateria', $idMateria);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();


        $count = $stmt->rowCount();

        if ($count >= 1) {

            $registro = $stmt->fetchAll();
            $mensaje = $registro;
            $error = 0;

        } else {
                $error = 101;
            }
        }
} else {
    $error = 2;
}

$datos['mensaje'] = $mensaje;
$datos['error'] = $error;

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos)

?>