<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . './cositas/Asesorias/resources/php/conn.php');

$obj_conexion = new ConexionBD();
$conn = $obj_conexion->getConexion();

$mensaje = "error";
$error = 1;

$datos_request = json_decode(file_get_contents("php://input"), true);

if (isset($datos_request['rol']) && isset($datos_request['id'])) {

    $rol = $datos_request['rol'];
    $id = $datos_request['id'];

    if (is_null($conn)) {
        $error = 100;
        //$mensaje = $obj_conexion->getMensaje();
    } else {

        //determina en cual tabla buscar dependiendo del rol
        if ($rol == "Asesor") {
            $tabla = "asesores";
            $columna_id = "idAsesor";
        } else {
            $tabla = "estudiantes";
            $columna_id = "idEstudiante";
        }


        $qry_info = "SELECT idAsesor, correo, carrera, semestre, fechaRegistro, nombre, apellidos
                        FROM :tabla
                        WHERE :columna_id = :id";


        $stmt = $conn->prepare($qry_info);
        // se le asigna un valor a la variable :correo, dentro del query
        $stmt->bindParam(':tabla', $tabla);
        $stmt->bindParam(':columna_id', $columna_id);
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count == 1) {

            $registro = $stmt->fetch();

            //comprueba si $registro obtuvo el valor
            if (isset($registro)) {
                $info = $registro;
                $mensaje = "OK";


                //CARGAR MATERIAS DEL ASESOR
                if($rol == "Asesor"){
                    //query recuperar materias del asesor

                    
                    $qry_materias = "SELECT m.nombre FROM materias m
                                    INNER JOIN asesores_materias am ON m.idMateria = am.idMateria
                                    WHERE am.idAsesor = :idAsesor";

                    $stmt_materias = $conn->prepare($qry_materias);
                    $stmt_materias->bindParam(':idAsesor', $id);
                    $stmt_materias->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt_materias->execute();
                    $materias = $stmt_materias->fetchAll();
                }

            } else {
                //error al obtener $registro
                $error = 102;
            }

        } else if ($count > 1) {
            // >1 filas        
            $error = 104;
        } else {
            //0 filas
            $error = 101;
        }
    }
} else {
    $error = 2;
}

$datos = [
    "mensaje" => $mensaje,
    "info" => $info,
    "materias" => $materias ?? null, //si existe la variable $materias, se asigna su valor, sino se asigna un valor nulo
    "error" => $error,
    "debug" => $debug ?? null //variable para debugging
];

header('Content-type: application/json; charset=utf-8');
echo json_encode($datos)

?>