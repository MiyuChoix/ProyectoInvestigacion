<?php
session_start();

if (!isset($_SESSION['ID'])) {

    header('Location: /cositas/Asesorias/bienvenida/');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Historial</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #e3d5f0;
        }

        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .navbar-custom {
            background: linear-gradient(90deg,
                    #6a0dad,
                    #8a2be2);
        }

        .historial-card {

            border: none;

            border-radius: 18px;

            transition: 0.2s ease;

            box-shadow:
                0 5px 12px rgba(0, 0, 0, 0.08);

        }

        .historial-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 15px 25px rgba(0, 0, 0, 0.15);

        }

        .estado-badge {

            padding:
                6px 12px;

            border-radius: 999px;

            color: white;

            font-size: 0.85rem;

            text-transform: capitalize;

        }

        .estado-pendiente {
            background: #ffc107;
            color: black;
        }

        .estado-aceptada {
            background: #198754;
        }

        .estado-terminada {
            background: #0d6efd;
        }

        .estado-cancelada {
            background: #dc3545;
        }

        .ver-perfil-btn {

            border-radius: 10px;

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
                        <a class="nav-link" href="/cositas/Asesorias/historial">Mis sesiones</a>
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

    <input type="hidden"
        id="rolActual"
        value="<?php echo $_SESSION['ROL']; ?>">

    <div class="container mt-4">

        <div class="d-flex
                    justify-content-between
                    align-items-center
                    mb-4">

            <h3 class="fw-bold">

                Historial de sesiones

            </h3>

        </div>

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <input
                            type="text"
                            id="busquedaNombre"
                            class="form-control"
                            placeholder="Buscar por nombre">

                    </div>

                    <div class="col-md-4">

                        <input
                            type="text"
                            id="busquedaMateria"
                            class="form-control"
                            placeholder="Buscar por materia">

                    </div>

                    <div class="col-md-3">

                        <input
                            type="date"
                            id="busquedaFecha"
                            class="form-control">

                    </div>

                    <div class="col-md-1 d-grid">

                        <button
                            class="btn btn-dark"
                            id="btnBuscar">

                            Buscar

                        </button>

                    </div>

                </div>

            </div>

        </div>

        <div id="contenedorHistorial"
            class="row g-3">

        </div>

        <div
    id="contenedorPaginacion"
    class="d-flex justify-content-center mt-4">
</div>

    </div>

    <?php if ($_SESSION['ROL'] === 'asesor'): ?>

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

    <script src="/cositas/Asesorias/resources/js/historial.js"></script>
    <script src="/cositas/Asesorias/resources/js/adminSesionAsesoria.js"></script>

</body>

</html>