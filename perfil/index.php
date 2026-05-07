<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <script defer src="/cositas/Asesorias/resources/js/perfil.js"></script>

    <title>Perfil</title>
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-lg">

            <div class="perfil-header bg-dark text-white p-4">
                <h4 class="m-0">Perfil</h4>
            </div>

            <div class="p-4">

                <div class="row align-items-center">

                    <div class="col-auto">
                        <img id="imagenPerfil"
                             class="rounded-circle border"
                             width="150"
                             height="150"
                             style="object-fit: cover;">
                    </div>

                    <div class="col">

                        <div class="d-flex align-items-center gap-3">

                            <div>
                                <h2 id="nombrePerfil" class="fw-bold mb-1"></h2>
                                <p id="carreraPerfil" class="text-muted fs-5 mb-1"></p>
                                <span id="tipoPerfil" class="badge bg-primary"></span>
                            </div>

                            <div id="accionesPerfil" class="ms-auto"></div>

                        </div>

                    </div>

                </div>

                <hr class="my-4">

                <div id="contenedorMaterias"></div>

                <h4 class="mb-3">Comunicación</h4>

                <div id="contenedorContactos"
                     class="row row-cols-1 row-cols-md-3 g-3">
                </div>

            </div>

        </div>

    </div>

    <!-- OFFCANVAS -->

    <div class="offcanvas offcanvas-start bg-dark text-white"
         tabindex="-1"
         id="editarPerfil">

        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title">Editar Perfil</h5>
            <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text"
                       id="editNombre"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">Carrera</label>
                <input type="text"
                       id="editCarrera"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">WhatsApp</label>
                <input type="text"
                       id="editWhatsapp"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">Instagram</label>
                <input type="text"
                       id="editInstagram"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">Discord</label>
                <input type="text"
                       id="editDiscord"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">Twitter</label>
                <input type="text"
                       id="editTwitter"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">Facebook</label>
                <input type="text"
                       id="editFacebook"
                       class="form-control bg-dark text-white">
            </div>

            <div class="mb-3">
                <label class="form-label">Imagen de perfil</label>
                <input type="file"
                       id="inputFoto"
                       class="form-control bg-dark text-white">
            </div>

            <button class="btn btn-primary w-100"
                    id="guardarPerfilBtn">
                Guardar cambios
            </button>

        </div>

    </div>

</body>
</html>