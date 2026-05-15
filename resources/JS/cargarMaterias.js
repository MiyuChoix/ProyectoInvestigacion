let paginaActual = 1;
let limite = 6;
let totalPaginas = 1;
let todasLasMaterias = [];

window.addEventListener("load", function () {

    cargarImagenPerfil(document.getElementById("rol").value, document.getElementById("id").value);

    revisarSolicitudes();

    setInterval(() => {
        revisarSolicitudes();
    }, 15000);

    // Boton, busqueda de materias
    let inputBusqueda = document.getElementById("inputBusqueda");

    document.querySelector(".search-btn").addEventListener("click", () => {
        let texto = inputBusqueda.value.toLowerCase();

        let filtradas = todasLasMaterias.filter(m =>
            m.nombre.toLowerCase().includes(texto)
        );

        renderMaterias(filtradas);
    });

    cargarMaterias();

});

async function cargarMaterias() {

    try {

        const respuesta = await fetch(

            `/cositas/Asesorias/resources/php/materias.php?pagina=${paginaActual}&limite=${limite}`

        );

        const datos = await respuesta.json();

        console.log(datos);

        if(datos.mensaje !== 'OK'){
            return;
        }

        todasLasMaterias = datos.materias;

        totalPaginas = datos.totalPaginas;

        renderMaterias(todasLasMaterias);

        renderPaginacion();

    } catch(error){

        console.log(error);

    }

}

function renderPaginacion() {

    const contenedor =
        document.getElementById(
            'paginacionMaterias'
        );

    contenedor.innerHTML = '';

    for(
        let i = 1;
        i <= totalPaginas;
        i++
    ){

        const btn =
            document.createElement('button');

        btn.classList.add(
            'btn',
            'btn-sm'
        );

        if(i === paginaActual){

            btn.classList.add(
                'btn-dark'
            );

        }else{

            btn.classList.add(
                'btn-outline-dark'
            );

        }

        btn.textContent = i;

        btn.addEventListener(
            'click',
            () => {

                paginaActual = i;

                cargarMaterias();

            }
        );

        contenedor.appendChild(btn);

    }

}

function renderMaterias(arreglo) {
    let contenedor = document.getElementById("contenedorMaterias");
    contenedor.innerHTML = '';

    arreglo.forEach(materia => {

        let divCol = document.createElement("div");
        divCol.classList.add("col-md-4");

        let divCard = document.createElement("div");
        divCard.classList.add("card", "card-custom");

        let boton = document.createElement("div");
        boton.setAttribute("data-bs-toggle", "modal");
        boton.setAttribute("data-bs-target", "#modalAsesores");
        boton.textContent = materia.nombre;

        boton.addEventListener("click", () => {
            cargarModalAsesores(materia.idMateria);
        });

        divCard.appendChild(boton);
        divCol.appendChild(divCard);
        contenedor.appendChild(divCol);
    });
}

function cargarModalAsesores(idMateria) {

    let datos = {
        idMateria: idMateria
    }

    fetch("/cositas/Asesorias/resources/php/modalAsesores.php", {
        method: 'POST',
        body: JSON.stringify(datos),
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(respuesta => {
            return respuesta.json();
        })
        .then(datos => {
            if (datos.mensaje == "error") {
                switch (datos.error) {
                    case 1: console.log("Error desconocido. Error: 1");
                        break;

                    case 100: console.log("La conexion es nula en el PHP. N. Error: 100");
                        break;

                    case 101: console.log("Error en la base de datos, no retorno filas. N. Error: 101");
                        break;

                    default: console.log("Error desconocido #2.");
                        break;
                }
            } else {
                let contenedor = document.getElementById("modalCuerpo");
                contenedor.innerHTML = '';

                let arregloAsesores = datos.mensaje;

                arregloAsesores.forEach(asesor => {

                    let divCol = document.createElement("div");
                    divCol.classList.add("col-12", "mb-3");

                    divCol.innerHTML = `
    
        <div class="card border-0 asesor-card h-100">

            <div class="card-body d-flex align-items-center">

                <img 
                    src="/cositas/Asesorias/resources/uploads/perfiles/perfil_asesor_${asesor.idAsesor}.png"
                    class="rounded-circle border me-3 foto-asesor"
                    onerror="this.src='/cositas/Asesorias/resources/img/default.png'"
                >

                <div class="flex-grow-1">

                    <h5 class="fw-bold mb-1">
                        ${asesor.nombre} ${asesor.apellidos}
                    </h5>

                    <p class="text-muted mb-2">
                        ${asesor.carrera || 'Sin carrera'}
                    </p>

                    <button class="btn btn-dark btn-sm ver-perfil-btn">
                        Ver perfil
                    </button>

                </div>

            </div>

        </div>

    `;

                    divCol.querySelector('.ver-perfil-btn')
                        .addEventListener('click', () => {

                            window.location =
                                "/cositas/Asesorias/perfil/?rol=asesor&id=" +
                                asesor.idAsesor;

                        });

                    contenedor.appendChild(divCol);

                });
            }
        })
        .catch(error => {
            console.log(error);
        });
}

let solicitudesMostradas = [];

async function revisarSolicitudes() {

    try {

        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/revisarSolicitudes.php'
        );

        const datos = await respuesta.json();

        console.log(datos);

        if (datos.mensaje !== 'OK') {
            return;
        }

        datos.sesiones.forEach(sesion => {

            if (
                solicitudesMostradas.includes(
                    sesion.idSolicitud
                )
            ) {
                return;
            }

            solicitudesMostradas.push(
                sesion.idSolicitud
            );

            mostrarNotificacion(sesion);

        });

    } catch(error) {

        console.log(error);

    }

}

function mostrarNotificacion(sesion) {

    const contenedor =
        document.getElementById(
            'contenedorNotificaciones'
        );

    const toast =
        document.createElement('div');

    toast.classList.add(
        'toast',
        'show',
        'mb-3'
    );

    toast.innerHTML = `

        <div class="toast-header">

            <strong class="me-auto">

                Nueva asesoría

            </strong>

            <small>
                pendiente
            </small>

        </div>

        <div class="toast-body">

            <div class="mb-2">

                <strong>Asesor:</strong>
                ${sesion.nombre}
                ${sesion.apellidos}

                <br>

                <strong>Materia:</strong>
                ${sesion.materia}

                <br>

                <strong>Fecha:</strong>
                ${sesion.fecha}

            </div>

            <div class="d-flex gap-2">

                <button
                    class="btn btn-success btn-sm aceptar-btn">

                    Aceptar

                </button>

                <button
                    class="btn btn-danger btn-sm rechazar-btn">

                    Rechazar

                </button>

            </div>

        </div>

    `;

    contenedor.appendChild(toast);

    toast
        .querySelector('.aceptar-btn')
        .addEventListener(
            'click',
            async () => {

                await actualizarSesion(
                    sesion.idSolicitud,
                    'aceptada'
                );

                toast.remove();

            }
        );

    toast
        .querySelector('.rechazar-btn')
        .addEventListener(
            'click',
            async () => {

                await actualizarSesion(
                    sesion.idSolicitud,
                    'cancelada'
                );

                toast.remove();

            }
        );

}

async function actualizarSesion(
    idSolicitud,
    estado
) {

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

        const datos =
            await respuesta.json();

        console.log(datos);

    } catch(error) {

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