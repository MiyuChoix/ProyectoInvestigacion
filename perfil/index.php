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

    <style>
        dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: none;
            border-radius: 10px;
            padding: 20px;
        }

        .card-custom {
            border-radius: 15px;
            overflow: hidden;
        }
    </style>

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

            <div class="text-end mt-4">

                <dialog id="ms">
                    <p class="text-center">¡La cuenta ha sido reportada con éxito!</p>
                    <div class="text-center">
                        <button id="cerrar" class="btn btn-danger">Cerrar</button>
                    </div>
                </dialog>

                <!-- OVERLAY -->
                <div id="overlay" style="
                        display: none;
                        position: fixed;
                        top: 0; left: 0;
                        width: 100vw; height: 100vh;
                        background-color: #363940;
                        z-index: 9999;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 20px;
                        ">

                    <img src="/cositas/Asesorias/resources/img/gato-no-encontrado(bingchiling).png" alt="Que anda viendo? Saquese" style="
                            position: absolute;
                            top: 15%; left: 10%;
                            width: 80%;
                            height: 80%;
                            object-fit: contain;
                            object-position: center;
                            ">

                    <div style="
                            position: 
                            relative; 
                            z-index: 10; 
                            align-self: 
                            flex-start;">

                        <button class="btn btn-outline-light m-1">
                            <a href="/cositas/Asesorias/" class="text-decoration-none text-white">
                                Regresar</a>
                        </button>

                    </div>

                    <div style="
                            position: relative; 
                            z-index: 10; 
                            text-align:center; 
                            margin-top: auto; 
                            padding-bottom: 40px;">
                        <h2
                            style="color: white; font-weight: bold; font-size: clamp(1.2rem, 2.5vw, 3rem); margin-bottom: 0;">
                            Whoops...</h2>
                        <p style="color: #ccc; font-size: clamp(0.6rem, 1.5vw, 1.5rem);">Este perfil no está disponible.
                        </p>
                    </div>
                </div>

                <div class="modal fade" id="modalReporte" tabindex="-1">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title">
                                    Reportar perfil
                                </h5>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal">
                                </button>

                            </div>

                            <div class="modal-body">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Motivo
                                    </label>

                                    <select
                                        id="inputMotivo"
                                        class="form-select">

                                        <option value="spam">
                                            Spam
                                        </option>

                                        <option value="acoso">
                                            Acoso
                                        </option>

                                        <option value="contenido_inapropiado">
                                            Contenido inapropiado
                                        </option>

                                        <option value="perfil_falso">
                                            Perfil falso
                                        </option>

                                        <option value="otro">
                                            Otro
                                        </option>

                                    </select>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Descripción
                                    </label>

                                    <textarea
                                        id="inputDescripcion"
                                        class="form-control"
                                        rows="4"></textarea>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                    Cancelar

                                </button>

                                <button
                                    id="btnEnviarReporte"
                                    class="btn btn-danger">

                                    Enviar reporte

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                <button id="show1" class="btn btn-danger m-3" data-bs-toggle="modal" data-bs-target="#modalReporte">
                    Reportar perfil
                </button>

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
<script>
    const tooltipTriggerList =
        document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipTriggerList.forEach(elemento => {

        new bootstrap.Tooltip(elemento);

    });
</script>

</html>