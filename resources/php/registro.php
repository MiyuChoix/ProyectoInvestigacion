<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();


$mensaje = "error";
$error = 1;


$datos_request = json_decode(file_get_contents("php://input"), true);


if (
    isset($datos_request['rol']) && isset($datos_request['nombre']) && isset($datos_request['apellidos']) && isset($datos_request['correo'])
    && isset($datos_request['pass']) && isset($datos_request['carrera'])
) {

    $rol = $datos_request['rol'];
    $nombre = $datos_request['nombre'];
    $apellidos = $datos_request['apellidos'];
    // anadir carrera
    $carrera = $datos_request['carrera'];
    $correo = $datos_request['correo'];
    $pass = $datos_request['pass'];


    $correo = trim(strtolower($correo));

    if (is_null($conn)) {
        $error = 100;
        //$mensaje = $obj_conexion->getMensaje();
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 104;
    } else {

        if ($rol == "asesor") {
            $rol = "asesores";
        } else {
            $rol = "estudiantes";
        }

        $qry_checar = "SELECT nombre
        FROM $rol
        where correo = :correo";

        // elimine la columna semestre
        // $qry_ingresar = "INSERT INTO :rol (correo, nControl, contrasena, carrera, nombre, apellidos) 
        // VALUES (:correo, NULL, :contrasena, :carrera, :nombre, :apellidos)";

        $stmt = $conn->prepare($qry_checar);
        // se le asigna un valor a la variable :correo, dentro del query
        $stmt->bindParam(':correo', $correo);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();


        $count = $stmt->rowCount();

        if ($count >= 1) {

            $mensaje = "error";
            $error = 102;
        } else {

            try{

            $qry_insertar = "INSERT INTO $rol (correo, nControl, contrasena, carrera, nombre, apellidos) 
                VALUES (:correo, NULL, :contrasena, :carrera, :nombre, :apellidos)";

            $stmt = $conn->prepare($qry_insertar);
            $stmt->bindParam(':correo', $correo);
            // $hash = password_hash($pass, PASSWORD_DEFAULT);
            //    $stmt->bindParam(':contrasena', $hash);
            // NO PUDE IMPLEMENTAR EL PASSWORD_HASH, NO SE PORQUE, SI ALGUIEN SABE PORQUE NO FUNCIONA, QUE BORRE LA LINEA ABAJO DE ESTO Y QUE des-COMENTE LAS DOS DE ARRIBA, LUEGO VAYAN A LAS LINEAS 46 Y 75 DE LOGUEO.PHP
            $stmt->bindParam(':contrasena', $pass);
            $stmt->bindParam(':carrera', $carrera);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos', $apellidos);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $mensaje = "OK";
                $error = 0;
            } else {
                $error = 101;
            }

        } catch (PDOException $e) {
            $error = 500;
            $debug = $e->getMessage();
        }
        }
    }
} else {
    $error = 2;
}

$datos['mensaje'] = $mensaje;
$datos['error'] = $error;

// debug
if (isset($debug)) {
    $datos["debug"] = $debug;
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos)

?>