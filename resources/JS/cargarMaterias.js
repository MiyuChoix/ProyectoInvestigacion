let arregloMaterias = [];

window.addEventListener("load", function () {


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
            if(datos.mensaje == "error"){
                switch (datos.error) {
                        case 1: console.log("La contrasena no es correcta. N. Error: 1");
                            break;

                        case 100: console.log("La conexion es nula en el PHP. N. Error: 100");
                            break;

                        case 101: console.log("Error en la base de datos, no retorno filas. N. Error: 101");
                            break;

                        default: console.log("Error desconocido.");
                            break;
                    }
            }else{

                arregloMaterias = datos.mensaje;
                renderMaterias(arregloMaterias);

            }
        })
        .catch(error => {
            console.log(error);
        });

});

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
            if(datos.mensaje == "error"){
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
            }else{
                let contenedor = document.getElementById("modalCuerpo");
                contenedor.innerHTML = '';
                let arregloAsesores = datos.mensaje;

                arregloAsesores.forEach(asesor => {

                    let divCol = document.createElement("div");
                    divCol.classList.add("col-md-12");

                    let divCard = document.createElement("div");
                    divCard.classList.add("card", "card-custom");

                    let boton = document.createElement("button");
                    boton.classList.add("btn", "btn-primary");
                    boton.textContent = asesor.nombre + " " + asesor.apellidos + "\n";
                    boton.addEventListener("click", () => {
                        window.location = "/cositas/Asesorias/perfil/asesor/?idAsesor=" + asesor.idAsesor;
                    });


                    let label = document.createElement("label");
                    label.classList.add("label", "label-custom", "m-2");
                    label.textContent = "  Carrera: " + asesor.carrera;
                    
                    
                    divCard.appendChild(boton);
                    divCol.appendChild(divCard);
                    contenedor.appendChild(divCol);
                    contenedor.appendChild(label);
                    contenedor.appendChild(document.createElement("hr"));
                });
            }
        })
        .catch(error => {
            console.log(error);
        });
}

function renderMaterias(arreglo) {
    let contenedor = document.getElementById("contenedorMaterias");
    contenedor.innerHTML = '';

    arreglo.forEach(materia => {

        let divCol = document.createElement("div");
        divCol.classList.add("col-md-4");

        let divCard = document.createElement("div");
        divCard.classList.add("card", "card-custom");

        let boton = document.createElement("button");
        boton.classList.add("btn", "btn-primary");
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