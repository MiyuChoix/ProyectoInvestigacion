let arregloMaterias = [];

window.addEventListener("load", function () {

    setInterval(() => {
        revisarSolicitudes();
    }, 5000);

    // Boton, busqueda de materias
    let inputBusqueda = document.getElementById("inputBusqueda");

    document.querySelector(".search-btn").addEventListener("click", () => {
        let texto = inputBusqueda.value.toLowerCase();

        let filtradas = todasLasMaterias.filter(m =>
            m.nombre.toLowerCase().includes(texto)
        );

        renderMaterias(filtradas);
    });



    let contenedor = document.getElementById("contenedorMaterias");

    fetch("/cositas/Asesorias/resources/php/materias.php", {
        method: 'GET',
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
                    case 1: console.log("No se. N. Error: 1");
                        break;

                    case 100: console.log("La conexion es nula en el PHP. N. Error: 100");
                        break;

                    case 101: console.log("Error en la base de datos, no retorno filas. N. Error: 101");
                        break;

                    default: console.log("Error desconocido.");
                        break;
                }
            } else {

                arregloMaterias = datos.mensaje;
                renderMaterias(arregloMaterias);

            }
        })
        .catch(error => {
            console.log(error);
        });

});

function renderMaterias(arreglo) {
    let contenedor = document.getElementById("contenedorMaterias");
    contenedor.innerHTML = '';

    arreglo.forEach(materia => {

        let divCol = document.createElement("div");
        divCol.classList.add("col-md-4");

        let divCard = document.createElement("div");
        divCard.classList.add("card", "card-custom", "materia-btn");

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
                                "/cositas/Asesorias/perfil/index.php?rol=asesor&id=" +
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

