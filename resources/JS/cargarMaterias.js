window.addEventListener("load", function () {

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


                let arregloMaterias = datos.mensaje;

                arregloMaterias.forEach(materia => {

                    let divCol = document.createElement("div");
                    divCol.classList.add("col-md-4");

                    let divCard = document.createElement("div");
                    divCard.classList.add("card", "card-custom");

                    let boton = document.createElement("button");
                    boton.classList.add("btn", "btn-primary");
                    boton.setAttribute("data-bs-toggle", "modal");
                    boton.setAttribute("data-bs-target", "#modalAsesores");
                    boton.textContent = materia.nombre;
                    // no funciona esto de abajo :)
                    boton.addEventListener("click", () => {
                        cargarModalAsesores(materia.idMateria);
                    });

                    divCard.appendChild(boton);
                    divCol.appendChild(divCard);
                    contenedor.appendChild(divCol);
                });


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
                    boton.textContent = asesor.nombre + " " + asesor.apellidos;
                    boton.addEventListener("click", () => {
                        window.location = "/cositas/Asesorias/perfil/asesor/?idAsesor=" + asesor.idAsesor;
                    });

                    let label = document.createElement("label");
                    label.classList.add("label", "label-custom");
                    label.textContent = "Carrera: " + asesor.carrera;

                    
                    divCard.appendChild(boton);
                    divCard.appendChild(label);
                    divCol.appendChild(divCard);
                    contenedor.appendChild(divCol);
                });
            }
        })
        .catch(error => {
            console.log(error);
        });
}