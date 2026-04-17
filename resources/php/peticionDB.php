<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');
        
$mensaje = "MAL";
$error = 1;
$correo = "default";

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();
    
$datos_request = json_decode(file_get_contents("php://input"), true);

if (isset($datos_request['correo'])){

$correo = $datos_request['correo'];

if(is_null($conn)){
    $error = 100;
    //$mensaje = $obj_conexion->getMensaje();
    $mensaje = "(is_null = true) on var conn";
}else{

    $qry_ingresar = "SELECT nControl
    FROM estudiantes
    where correo = :correo";

        //statement - declaracion
    $stmt = $conn->prepare($qry_ingresar);
  
    $stmt->bindParam(':correo', $correo);
    
        //Se puede establecer que regrese una arreglo asociativo antes de ejecutar la consulta
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $stmt->execute();
    $count = $stmt->rowCount();     
    
        
    if($count >= 1){
        $registro = $stmt->fetch(); 
        $mensaje = $registro;
    }else{
        $mensaje = "Error en la base de datos, no retorno filas";
        $error = 101;
    }

}

}else{
    $mensaje = "No llegaron los datos al PHP";
    $error = 2;
}

$datos['mensaje'] = $mensaje;
$datos['error'] = $error;

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos)

?>