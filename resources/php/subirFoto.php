<?php

session_start();

header('Content-Type: application/json');

if(
    !isset($_SESSION['ID']) ||
    !isset($_SESSION['ROL'])
){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'Sesion invalida'
    ]);

    exit;
}

if(!isset($_FILES['foto'])){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'No llego archivo'
    ]);

    exit;
}

$archivo = $_FILES['foto'];

if($archivo['error'] !== 0){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'Error upload: ' . $archivo['error']
    ]);

    exit;
}

if($archivo['size'] > 5 * 1024 * 1024){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'Archivo muy grande'
    ]);

    exit;
}

$mime =
    mime_content_type(
        $archivo['tmp_name']
    );

$permitidos = [
    'image/jpeg',
    'image/png',
    'image/webp'
];

if(!in_array($mime, $permitidos)){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'Formato invalido'
    ]);

    exit;
}


switch($mime){

    case 'image/jpeg':

        $imagen =
            @imagecreatefromjpeg(
                $archivo['tmp_name']
            );

        break;

    case 'image/png':

        $imagen =
            @imagecreatefrompng(
                $archivo['tmp_name']
            );

        break;

    case 'image/webp':

        $imagen =
            @imagecreatefromwebp(
                $archivo['tmp_name']
            );

        break;

    default:

        echo json_encode([
            'mensaje' => 'error'
        ]);

        exit;
}

if(!$imagen){

    echo json_encode([
        'mensaje' => 'error',
        'error' => 'No se pudo procesar la imagen'
    ]);

    exit;
}

$rol = $_SESSION['ROL'];
$id = $_SESSION['ID'];

$nombre =
    "perfil_{$rol}_{$id}.png";

$ruta_fisica =
    $_SERVER['DOCUMENT_ROOT'] .
    "/cositas/Asesorias/resources/uploads/perfiles/" .
    $nombre;

imagepng(
    $imagen,
    $ruta_fisica
);

imagedestroy($imagen);

$ruta_publica =
    "/cositas/Asesorias/resources/uploads/perfiles/" .
    $nombre;

echo json_encode([
    'mensaje' => 'OK',
    'ruta' => $ruta_publica
]);

?>