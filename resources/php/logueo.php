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
    $correo = trim(strtolower($correo));

    $pass = $datos_request['pass'];

    if (is_null($conn)) {
        $error = 100;
        //$mensaje = $obj_conexion->getMensaje();
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 104;
    } else {

        $qry_ingresar = "SELECT idAsesor, contrasena
            FROM asesores
            where correo = :correo";


        $stmt = $conn->prepare($qry_ingresar);
        // se le asigna un valor a la variable :correo, dentro del query
        $stmt->bindParam(':correo', $correo);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {

            $registro = $stmt->fetch();

            //$hash = $registro['contrasena'];
            //intente implementar password_verify($pass, $hash) en la condicion del if, pero NO FUNCIONA, NO TENGO MALDITA IDEA DE PORQUE, HIJUEPUT
            if ($pass == $registro['contrasena']) {
                $mensaje = "OK";
                $error = 0;
                //sesion ok
            }else{
                $error = 103;
                // $debug = "Contrasena de la DB: " . $hash . ", Contrasena ingresada: " . $pass;
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

            if ($stmt->rowCount() == 1) {

                $registro = $stmt->fetch();
                // $hash = $registro['contrasena'];
                if ($pass == $registro['contrasena']) {
                    $mensaje = "OK";
                    $error = 0;
                    //sesion ok
                }else{
                    $error = 103;
                    // $debug = "Contrasena de la DB: " . $registro['contrasena'] . ", Contrasena ingresada: " . $pass;
                }

            } else {
                $error = 101;
            }
        }
    }
} else {
    $error = 2;
}

$datos = [
    "mensaje" => $mensaje,
    "error" => $error,
    "debug" => $debug ?? null
];

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos)
?>