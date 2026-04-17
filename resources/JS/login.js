window.addEventListener("load", function () {
    document.getElementById('form').addEventListener("submit", formSubmit);
    console.log("Hola mundo, se activo el evento LOAD. :)");
});

function formSubmit(event) {

    event.preventDefault();

    

        let correo = document.getElementById('correo').value.trim();
        let password = document.getElementById('password').value.trim();

    if (correo && password) {

        let datos = {
            correo: correo,
            pass: password
        }
        fetch("/cositas/Asesorias/resources/php/logueo.php", {
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

                        default: console.log("Error desconocido.");
                            break;
                    }
                } else if (datos.mensaje == "OK") {
                   window.location = "/cositas/Asesorias";
                }
            })
            .catch(error => {
                console.log(error);
            });

        document.getElementById('password').value = '';
    }
    return false;
}