window.addEventListener('load', () => {

    cargarMaterias();

    cargarSesiones();

    cargarImagenPerfil(document.getElementById("rol").value, document.getElementById("id").value);
    // refresca cada minuto
    setInterval(cargarSesiones, 60000);

    document
        .getElementById('btnEnviarSolicitud')
        .addEventListener('click', enviarSolicitud);

});

async function cargarMaterias() {

    try {

        // no se puede usar materias.php porque se necesitan las materias especificas de cada asesor, sino podrian asesorar materias que no han cursado
        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/materiasAsesor.php'
        );

        const datos = await respuesta.json();

        console.log(datos);

        if (datos.mensaje !== 'OK') {
            return;
        }

        const select =
            document.getElementById('inputMateria');

        datos.materias.forEach(materia => {

            const option =
                document.createElement('option');

            option.value =
                materia.idMateria;

            option.textContent =
                materia.nombre;

            select.appendChild(option);

        });

    } catch (error) {

        console.log(error);

    }

}

async function enviarSolicitud() {

    const datos = {

        idEstudiante:
            document.getElementById('inputIdEstudiante').value,

        idMateria:
            document.getElementById('inputMateria').value,

        fecha:
            document.getElementById('inputFecha').value

    };

    console.log(datos);

    try {

        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/crearSesionAsesoria.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify(datos)
            }
        );

        const resultado =
            await respuesta.json();

        console.log(resultado);

        if (resultado.mensaje === 'OK') {

            alert('Solicitud enviada');

            location.reload();

        } else {

            alert(resultado.error);

        }

    } catch (error) {

        console.log(error);

    }

}

async function cargarSesiones() {

    try {

        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/cargarSesiones.php'
        );

        const datos = await respuesta.json();

        console.log(datos);

        if (datos.mensaje !== 'OK') {
            return;
        }

        renderSesiones(datos.sesiones);

    } catch (error) {

        console.log(error);

    }

}

function renderSesiones(sesiones) {

    const contenedor =
        document.getElementById('contenedorSesiones');

    contenedor.innerHTML = '';

    if (sesiones.length === 0) {

        contenedor.innerHTML = `

            <div class="col-12">

                <div class="alert alert-secondary">

                    No hay sesiones recientes.

                </div>

            </div>

        `;

        return;

    }

    sesiones.forEach(sesion => {

        const col =
            document.createElement('div');

        col.classList.add('col-md-6');

        let botones = '';

        if (sesion.estado === 'aceptada') {

            botones += `
            
                <button
                    class="btn btn-primary btn-sm finalizar-btn">

                    Finalizar

                </button>
            
            `;
        }

        if (
            sesion.estado !== 'terminada' &&
            sesion.estado !== 'cancelada'
        ) {

            botones += `
    
        <button
            class="btn btn-danger btn-sm cancelar-btn">

            Cancelar

        </button>
    
    `;

        }

        col.innerHTML = `

            <div class="card sesion-card h-100">

                <div class="card-body">

                    <div class="
                        d-flex
                        justify-content-between
                        align-items-start
                        mb-3
                    ">

                        <div>

                            <div class="d-flex align-items-center gap-2 mb-1">

    <h5 class="fw-bold mb-0">

        ${sesion.nombre}
        ${sesion.apellidos}

    </h5>

    <button class="btn btn-dark btn-sm ver-perfil-btn perfil-btn">

        Ver perfil

    </button>

</div>

                            <p class="text-muted mb-0">

                                ${sesion.carrera || 'Sin carrera'}

                            </p>

                        </div>

                        <span class="
                            estado-badge
                            estado-${sesion.estado}
                        ">

                            ${sesion.estado}

                        </span>

                    </div>

                    <div class="mb-3">

                        <strong>Materia:</strong>
                        ${sesion.materia || 'Sin materia'}

                        <br>

                        <strong>Fecha:</strong>
                        ${sesion.fecha}

                    </div>

                    <div class="d-flex gap-2">

                        ${botones}

                    </div>

                </div>

            </div>

        `;

        const cancelarBtn =
            col.querySelector('.cancelar-btn');

        if (cancelarBtn) {

            cancelarBtn.addEventListener(
                'click',
                () => actualizarEstado(
                    sesion.idSolicitud,
                    'cancelada'
                )
            );

        }

        const finalizarBtn =
            col.querySelector('.finalizar-btn');

        if (finalizarBtn) {

            finalizarBtn.addEventListener(
                'click',
                () => actualizarEstado(
                    sesion.idSolicitud,
                    'terminada'
                )
            );

        }

        const perfilBtn =
            col.querySelector('.perfil-btn');

        if (perfilBtn) {

            perfilBtn.addEventListener(
                'click',
                () => {

                    window.location.href =
                        `/cositas/Asesorias/perfil/index.php?rol=estudiante&id=${sesion.idEstudiante}`;

                }
            );

        }

        contenedor.appendChild(col);

    });

}

async function actualizarEstado(idSolicitud, estado) {

    try {

        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/actualizarSesion.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({
                    idSolicitud,
                    estado
                })
            }
        );

        const datos = await respuesta.json();

        console.log(datos);

        cargarSesiones();

    } catch (error) {

        console.log(error);

    }

}

function cargarImagenPerfil(rol, id) {

    const imagen =
        document.getElementById('imagenPerfil');

    imagen.src =
        `/cositas/Asesorias/resources/uploads/perfiles/perfil_${rol}_${id}.png`;

    imagen.onerror = function () {

        this.onerror = null;

        this.src =
            '/cositas/Asesorias/resources/img/default.png';
    };
}