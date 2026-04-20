window.addEventListener("load", function () {
    document.getElementById('form').addEventListener("submit", formSubmit);
    console.log("Hola mundo, se activo el evento LOAD. :)");
});

function formSubmit(event) {

    event.preventDefault();

    const form = event.target;

    // Validación HTML5 primero
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    let nombre = document.getElementById('nombre').value.trim();
    let apellidos = document.getElementById('apellidos').value.trim();
    let correo = document.getElementById('correo').value.trim();
    let carrera = document.getElementById('carrera').value;
    let password = document.getElementById('password').value.trim();
    let passConfirm = document.getElementById('passConfirm');
    let rol = document.getElementById('rol').value;

    if (!rol) {
        alert("Selecciona un rol... Por favor.");
        return;
    }

    // Validación personalizada de la contraseña
    if (password !== passConfirm.value.trim()) {
        passConfirm.value = '';
        passConfirm.setCustomValidity("Las contraseñas no coinciden.");
        passConfirm.reportValidity();
        return;
    }

    passConfirm.setCustomValidity("");

    // segunda capa de seguridad del select, por si acaso xd
    if (!carrera) {
        document.getElementById('carrera').setCustomValidity("Selecciona una carrera");
        document.getElementById('carrera').reportValidity();
        return;
    } else {
        document.getElementById('carrera').setCustomValidity("");
    }


    let datos = {
        rol: rol,
        nombre: nombre,
        apellidos: apellidos,
        carrera: carrera,
        correo: correo,
        pass: password
    }

    fetch("/cositas/Asesorias/resources/php/registro.php", {
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

                    case 102: console.log("El usuario ya existe. N. Error: 102");
                        break;

                    case 500: console.log("Error en la consulta, imprimiendo error. N. Error: 500");
                              console.log(datos.debug);
                        break;

                    default: console.log("Error desconocido.");
                        break;
                }
            } else if (datos.mensaje == "OK") {
                registroExitoso();
            }
        })
        .catch(error => {
            console.log(error);
        });
}

