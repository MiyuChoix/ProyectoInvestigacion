<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>index</title>
    <style>
        body {
            background-color: #e3d5f0;
        }

        .navbar-custom {
            background: linear-gradient(90deg, #6a0dad, #8a2be2);
        }

        .search-box {
            max-width: 500px;
            margin: 20px auto;
        }

        .search-input {
            border-radius: 25px 0 0 25px;
            border: 1px solid #ccc;
        }

        .search-btn {
            border-radius: 0 25px 25px 0;
            background-color: #6a0dad;
            color: white;
        }

        .nav-link.active {
            border-bottom: 2px solid white;
        }

        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .navbar-collapse .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-custom {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            background-color: #6a0dad;
            color: white;

            font-weight: bold;

            text-align: center;

            padding: 20px;

            border-radius: 15px;

            cursor: pointer;

            transition: all 0.2s ease;
            user-select: none;
        }

        .card-custom:hover {

            background-color: #8a2be2;

            transform: translateY(-2px);

        }

        .card-footer-custom {
            background-color: #6a0dad;
            height: 25px;
        }

        /* Estilo para modal de asesores */
        .asesor-card {
            border-radius: 18px;
            transition: all 0.25s ease;
            background: white;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .asesor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 20px rgba(0, 0, 0, 0.2);
        }

        .foto-asesor {
            width: 70px;
            height: 70px;
            object-fit: cover;
        }

        .ver-perfil-btn {
            border-radius: 10px;
            padding-left: 16px;
            padding-right: 16px;
        }
        .modal-content {
    border-radius: 20px;
    overflow: hidden;
}

.form-control,
.form-select {
    border-radius: 12px;
    padding: 12px;
}

#btnEnviarSolicitud {
    border-radius: 12px;
    transition: all 0.2s ease;
}

#btnEnviarSolicitud:hover {
    transform: translateY(-2px);
}

.sesion-card{

    border-radius: 18px;

    border: none;

    transition: 0.2s ease;

    box-shadow: 0 5px 10px rgba(0,0,0,0.08);

}

.sesion-card:hover{

    transform: translateY(-3px);

    box-shadow: 0 15px 20px rgba(0,0,0,0.15);

}

.estado-badge{

    font-size: 0.85rem;

    padding: 8px 12px;

    border-radius: 999px;

    user-select: none;
}

.estado-pendiente{
    background: #ffc107;
    color: black;
}

.estado-aceptada{
    background: #198754;
    color: white;
}

.estado-terminada{
    background: #0d6efd;
    color: white;
}

.estado-cancelada{
    background: #dc3545;
    color: white;
}
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav me-auto ms-4">
                    <li class="nav-item">
                        <a class="nav-link" href="/cositas/Asesorias/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Mis sesiones</a>
                    </li>
                </ul>

                <?php if ($_SESSION['ROL'] === 'asesor'): ?>

                    <button class="btn btn-success me-3"
                        data-bs-toggle="modal"
                        data-bs-target="#modalSesion">

                        Iniciar asesoría

                    </button>

                <?php endif; ?>
                <div>
                    <a class="navbar-brand" href="/cositas/Asesorias/perfil/?rol=<?php echo $_SESSION['ROL']; ?>&id=<?php echo $_SESSION['ID']; ?>">
                        <input type="hidden" id="id" value="<?php echo $_SESSION['ID']; ?>">
                        <input type="hidden" id="rol" value="<?php echo $_SESSION['ROL']; ?>">
                        <img id="imagenPerfil" class="profile-img object-fit-cover">
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="search-box">
        <div class="input-group">
            <input id="inputBusqueda" class="form-control search-input  me-2" type="text" placeholder="Buscar materias... (Calculo, Fisica, etc.)">
            <button class="btn btn-primary search-btn" type="button">Buscar</button>
        </div>
    </div>
    <hr>

    <?php if ($_SESSION['ROL'] === 'estudiante'): ?>

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold">Materias</h5>
            </div>


            <div id="contenedorMaterias" class="row g-3 mb-4">
                <!-- la clase "col-md-#" es la que controla cuantos elementos van dentro de las columnas antes de cambiar de linea.
                         bootstrap utiliza un sistema de 12 columnas para colocar sus elementos, asi que colocando un valor de 4 se haria
                         el calculo 12/4 y terminaria con 3 elementos por fila.-->
            </div>
        </div>

        <div class="modal fade" id="modalAsesores" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Asesores disponibles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div id="modalCuerpo" class="modal-body">

                    </div>

                </div>
            </div>
        </div>

    <?php endif; ?>

    <?php if ($_SESSION['ROL'] === 'asesor'): ?>

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold">Sesiones</h5>
            </div>


            <div id="contenedorSesiones" class="row g-3 mb-4">

            </div>
        </div>

        <div class="modal fade" id="modalSesion" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg">

                    <div class="modal-header bg-dark text-white">

                        <h5 class="modal-title fw-bold">
                            Nueva asesoría
                        </h5>

                        <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <div class="modal-body p-4">

                        <!-- ID ESTUDIANTE -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                ID del estudiante
                            </label>

                            <input type="number"
                                id="inputIdEstudiante"
                                class="form-control"
                                placeholder="Ejemplo: 15">

                        </div>

                        <!-- MATERIA -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Materia
                            </label>

                            <select id="inputMateria"
                                class="form-select">

                                <option value="">
                                    Selecciona una materia
                                </option>
                                

                            </select>

                        </div>

                        <!-- FECHA -->

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Fecha acordada
                            </label>

                            <input type="datetime-local"
                                id="inputFecha"
                                class="form-control">

                        </div>

                        <!-- BOTÓN -->

                        <button class="btn btn-success w-100 py-2 fw-bold"
                            id="btnEnviarSolicitud">

                            Enviar solicitud

                        </button>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <div
    id="contenedorNotificaciones"
    class="toast-container position-fixed bottom-0 start-0 p-3"
    style="z-index: 9999;"
></div>

</body>
<!-- Redirige cuando no hay $_SESSION['ID'], ESTE SNIPPET SE IMPLEMENTA EN TODOS LOS ARCHIVOS -->
<?php if (!isset($_SESSION['ID'])): ?>

    <script>
        window.location.href = "/cositas/Asesorias/bienvenida/";
    </script>

<?php endif; ?>

<?php if ($_SESSION['ROL'] === 'estudiante'): ?>

    <script src="/cositas/Asesorias/resources/js/cargarMaterias.js?v=" <?php echo time(); ?>></script>

<?php endif; ?>

<?php if ($_SESSION['ROL'] === 'asesor'): ?>

    <script src="/cositas/Asesorias/resources/js/adminSesionAsesoria.js?v=" <?php echo time(); ?>></script>
    
<?php endif; ?>


</html>