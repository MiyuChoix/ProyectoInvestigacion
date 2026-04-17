<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();


$mensaje = "error";
$error = 1;


$datos_request = json_decode(file_get_contents("php://input"), true);


if (isset($datos_request['correo']) && isset($datos_request['pass'])) {

    $correo = $datos_request['correo'];
    $pass = $datos_request['pass'];


    if (is_null($conn)) {
        $error = 100;
        //$mensaje = $obj_conexion->getMensaje();
    } else {


        $qry_ingresar = "SELECT idAsesor, contrasena
    FROM asesores
    where correo = :correo";


        $stmt = $conn->prepare($qry_ingresar);
        // se le asigna un valor a la variable :correo, dentro del query
        $stmt->bindParam(':correo', $correo);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();


        $count = $stmt->rowCount();

        if ($count == 1) {

            $registro = $stmt->fetch();

            if ($registro['contrasena'] == $pass) {
                $mensaje = "OK";
                $error = 0;
                //sesion ok
            }
        } else {


            $qry_ingresar = "SELECT idEstudiante, contrasena
        FROM estudiantes
        where correo = :correo";


            $stmt = $conn->prepare($qry_ingresar);
            $stmt->bindParam(':correo', $correo);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();


            $count = $stmt->rowCount();

            if ($count == 1) {

                $registro = $stmt->fetch();
                if ($registro['contrasena'] == $pass) {
                    $mensaje = "OK";
                    $error = 0;
                    //sesion ok
                }
            } else {
                $error = 101;
            }
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