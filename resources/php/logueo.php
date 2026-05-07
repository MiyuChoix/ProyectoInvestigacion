<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/cositas/Asesorias/resources/php/conn.php');
session_start();

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

        $tablas = [
            [
                "tabla" => "asesores",
                "id" => "idAsesor",
                "rol" => "asesor"
            ],
            [
                "tabla" => "estudiantes",
                "id" => "idEstudiante",
                "rol" => "estudiante"
            ]
        ];

        foreach ($tablas as $item) {

            $query = "
            SELECT {$item['id']} AS id, contrasena
            FROM {$item['tabla']}
            WHERE correo = :correo
        ";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            $registro = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($registro) {

                if (
                    $pass == $registro['contrasena'] ||
                    password_verify($pass, $registro['contrasena'])
                ) {

                    session_regenerate_id(true);

                    $_SESSION['ID'] = $registro['id'];
                    $_SESSION['ROL'] = $item['rol'];
                    $_SESSION['BLOCK'] = false;
                    $_SESSION['ACCESO'] = time();

                    $mensaje = "OK";
                    $error = 0;
                } else {

                    $error = 103;
                }

                break;
            }

            $error = 101;
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