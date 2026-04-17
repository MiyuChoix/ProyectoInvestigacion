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
                    boton.textContent = materia.nombre;
                    // no funciona esto de abajo :)
                    boton.addEventListener("click", console.log("Cargar modal de asesores para la materia con id: " + materia.idMateria));

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
    // Cargar modal de asesores xd
}