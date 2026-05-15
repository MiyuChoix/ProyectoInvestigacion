window.addEventListener('load', () => {

    cargarHistorial(1);
    cargarImagenPerfil(document.getElementById("rol").value, document.getElementById("id").value);

document
    .getElementById('btnBuscar')
    .addEventListener('click', () => {

        paginaActual = 1;

        cargarHistorial(1);

    });

});

let paginaActual = 1;

const LIMITE = 6;

async function cargarHistorial(pagina = 1) {

    try {

        const datosBusqueda = {

            pagina,

            limite: LIMITE,

            nombre:
                document.getElementById(
                    'busquedaNombre'
                ).value,

            materia:
                document.getElementById(
                    'busquedaMateria'
                ).value,

            fecha:
                document.getElementById(
                    'busquedaFecha'
                ).value

        };

        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/cargarHistorial.php',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify(
                    datosBusqueda
                )
            }
        );

        const datos =
            await respuesta.json();

            console.log(datos);

        if(datos.mensaje !== 'OK'){
            return;
        }

        paginaActual = pagina;

        renderHistorial(
            datos.historial
        );

        renderPaginacion(
            datos.totalPaginas
        );

    } catch(error){

        console.log(error);

    }

}

function renderHistorial(historial) {

    const rolActual =
        document.getElementById('rolActual').value;

    const contenedor =
        document.getElementById(
            'contenedorHistorial'
        );

    contenedor.innerHTML = '';

    if (historial.length === 0) {

        contenedor.innerHTML = `

            <div class="col-12">

                <div class="alert alert-secondary">

                    No hay sesiones
                    en el historial.

                </div>

            </div>

        `;

        return;

    }

    historial.forEach(sesion => {

        const col =
            document.createElement('div');

        col.classList.add('col-md-6');

        col.innerHTML = `

            <div class="
                card
                historial-card
                h-100
            ">

                <div class="card-body">

                    <div class="
                        d-flex
                        justify-content-between
                        align-items-start
                        mb-3
                    ">

                        <div>

                            <div class="
                                d-flex
                                align-items-center
                                gap-2
                                mb-1
                            ">

                                <h5 class="
                                    fw-bold
                                    mb-0
                                ">

                                    ${sesion.nombre}
                                    ${sesion.apellidos}

                                </h5>

                                <button class="
                                    btn
                                    btn-dark
                                    btn-sm
                                    ver-perfil-btn
                                    perfil-btn
                                ">

                                    Ver perfil

                                </button>

                            </div>

                            <p class="
                                text-muted
                                mb-0
                            ">

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

                    <div>

                        <strong>Materia:</strong>

                        ${sesion.materia || 'Sin materia'}

                        <br>

                        <strong>Fecha:</strong>

                        ${sesion.fecha}

                    </div>

                </div>

            </div>

        `;

        const perfilBtn =
            col.querySelector('.perfil-btn');

        perfilBtn.addEventListener(
            'click',
            () => {

                let rolDestino;
                let idDestino;

                if (rolActual === 'asesor') {

                    rolDestino = 'estudiante';

                    idDestino =
                        sesion.idEstudiante;

                } else {

                    rolDestino = 'asesor';

                    idDestino =
                        sesion.idAsesor;

                }

                window.location.href =
                    `/cositas/Asesorias/perfil/index.php?rol=${rolDestino}&id=${idDestino}`;

            }
        );

        contenedor.appendChild(col);

    });

}

function renderPaginacion(totalPaginas){

    const contenedor =
        document.getElementById(
            'contenedorPaginacion'
        );

    contenedor.innerHTML = '';

    if(totalPaginas <= 1){
        return;
    }

    for(
        let i = 1;
        i <= totalPaginas;
        i++
    ){

        const boton =
            document.createElement('button');

        boton.classList.add(
            'btn',
            'btn-sm',
            'mx-1'
        );

        if(i === paginaActual){

            boton.classList.add(
                'btn-dark'
            );

        }else{

            boton.classList.add(
                'btn-outline-dark'
            );

        }

        boton.textContent = i;

        boton.addEventListener(
            'click',
            () => {

                cargarHistorial(i);

            }
        );

        contenedor.appendChild(
            boton
        );

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