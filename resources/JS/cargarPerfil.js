document.addEventListener('load', function () {

    let contenedor = document.getElementById("contenedorMaterias");

    //se toma la url y se divide en ROL (?idAsesor o ?idEstudiante) y el ID del asesor o alumno para buscar en la table respectiva
    const params = new URLSearchParams(window.location.search);
    const key = [...params.keys()][0];
    const rol = key.substring(2);
    //"idEstudiante" o "idAsesor" -> "Estudiante" o "Asesor"
    const id = params.get(key);

    let datos = {
        rol: rol,
        id: id
    }

    fetch("/cositas/Asesorias/resources/php/cargarPerfil.php", {
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
                    case 1: console.log("La contrasena no es correcta. N. Error: 1");
                        break;

                    case 2: console.log("Los datos no llegaron al PHP. N. Error: 2");
                        break;

                    case 100: console.log("La conexion es nula en el PHP. N. Error: 100");
                        break;

                    case 101: console.log("Error en la base de datos, no retorno filas. N. Error: 101");
                        break;

                    case 104: console.log("Error en la base de datos, retorno mas de una fila. N. Error: 101");
                        break;

                    default: console.log("Error desconocido.");
                        break;
                }

                if (datos.debug != null) {
                    console.log(datos.debug);
                }

            } else {

                let infoAsesor = datos.info;

                let nombrePerfil = document.getElementById("nombreAlumno");
                nombrePerfil.textContent = infoAsesor.nombre + "" + infoAsesor.apellidos;

                let carerraPerfil = document.getElementById("carreraPerfil");
                carerraPerfil.textContent = "Estudiante de " + infoAsesor.carerra;

                let telefonoPerfil = document.getElementById("telefonoPerfil");
                telefonoPerfil.textContent = infoAsesor.contacto;

                let igPerfil = document.getElementById("idPerfil");
                igPerfil.textContent = "FAHHHHHHHH";

                let tipoPerfil = document.getElementById("tipoPerfil");
                tipoPerfil.textContent = rol;


                if (datos.materias != null) {


                    let contenedor = document.getElementById("contenedor");
                    contenedor.innerHTML = '';

                    let ashe = document.createElement("h4");
                    ashe.classList.add("mb-3");
                    ashe.textContent = "Disponibilidad de materias";
                    contenedor.appendChild(ashe);

                    let scrollContainer = document.createElement("div");
                    scrollContainer.classList.add("scroll-container");

                    let hr = document.createElement("hr");
                    hr.classList.add("my-4");
                    contenedor.appendChild(hr);

                    let materias = datos.materias;
                    materias.forEach(element => {

                        let divCol = document.createElement("div");
                        divCol.classList.add("col-md-4");

                        let divCard = document.createElement("div");
                        divCard.classList.add("card", "card-custom");

                        let divCardBody = document.createElement("div");
                        divCardBody.classList.add("card-body");

                        let h5 = document.createElement("h5");
                        h5.classList.add("card-title");
                        h5.textContent = element.nombre;

                        divCardBody.appendChild(h5);
                        divCard.appendChild(divCardBody);
                        divCol.appendChild(divCard);
                        contenedor.appendChild(divCol);


                    });

                }
            }
        })
        .catch(error => {
            console.log(error);
        });

});