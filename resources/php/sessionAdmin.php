<?php
// tiempo de inactividad: 2 horas
define('TIEMPO_INACTIVIDAD', 7200);

// Configurar cookie (antes de session_start)
session_set_cookie_params([
    'lifetime' => TIEMPO_INACTIVIDAD,
    'path' => '/',
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']), // false si no es HTTPS
    'samesite' => 'Lax'
]);

session_start();

// sesion
if (!isset($_SESSION['ID'])) {
    header("Location: login.html");
    exit;
}


// bloqueo

if(isset($_SESSION['BLOCK']) && $_SESSION['BLOCK'] === true){
    session_unset();
    session_destroy();
    header("Location: login.html?expirado=1");
    exit;
}

// verificar inactividad
if (isset($_SESSION['ACCESO'])) {

    $tiempo_transcurrido = time() - $_SESSION['ACCESO'];

    if ($tiempo_transcurrido > TIEMPO_INACTIVIDAD) {
        session_unset();
        session_destroy();

        header("Location: login.html?expirado=1");
        exit;
    }

}

// actualizar actividad si no ha expirado
$_SESSION['ACCESO'] = time();
session_regenerate_id(true);

?>