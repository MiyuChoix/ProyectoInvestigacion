window.addEventListener('DOMContentLoaded', async function () {

    document.getElementById('inputFoto').addEventListener('change', function () {

        const archivo = this.files[0];

        if (!archivo) {
            return;
        }

        const lector = new FileReader();

        lector.onload = function (e) {
            document.getElementById('imagenPerfil').src =
                e.target.result;
        };

        lector.readAsDataURL(archivo);

    });

    const params = new URLSearchParams(window.location.search);

    const rol = params.get('rol');
    const id = params.get('id');

    if (!rol || !id) {
        window.location.href = '/cositas/Asesorias/index.html';
        return;
    }

    try {

        const respuesta = await fetch('/cositas/Asesorias/resources/php/cargarPerfil.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                rol,
                id
            })
        });

        const datos = await respuesta.json();

        console.log(datos);

        if (datos.mensaje !== 'OK') {
            console.log(datos);
            return;
        }

        const info = datos.info;

        cargarPerfil(info, datos.rol);
        cargarContactos(info);
        cargarImagenPerfil(datos.rol, info.id);

        if (datos.rol === 'asesor') {
            cargarMaterias(datos.materias);
        }

        if (datos.propio) {
            mostrarBotonEditar(info);
        } else {
            const offcanvas = document.getElementById('editarPerfil');

            if (offcanvas) {
                offcanvas.remove();
            }
        }

    } catch (error) {
        console.log(error);
    }


});

function cargarPerfil(info, rol) {

    document.getElementById('nombrePerfil').textContent =
        `${info.nombre} ${info.apellidos}`;

    document.getElementById('carreraPerfil').textContent =
        info.carrera || 'Sin carrera';

    if (rol === 'asesor') {
        rol = 'Asesor';
    } else {
        rol = 'Estudiante'
    }

    document.getElementById('tipoPerfil').textContent = rol;

}

function cargarContactos(info) {

    const contactos = [
        {
            valor: info.telefono,
            icono: 'bi-whatsapp',
            link: `https://wa.me/${info.telefono}`
        },
        {
            valor: info.instagram,
            icono: 'bi-instagram',
            link: `https://instagram.com/${info.instagram}`
        },
        {
            valor: info.discord,
            icono: 'bi-discord',
            link: `https://discordapp.com/users/${info.discord}`
        },
        {
            valor: info.twitter,
            icono: 'bi-twitter-x',
            link: `https://twitter.com/${info.twitter}`
        },
        {
            valor: info.facebook,
            icono: 'bi-facebook',
            link: `https://facebook.com/${info.facebook}`
        }
    ];

    const contenedor = document.getElementById('contenedorContactos');

    contactos.forEach(contacto => {

        if (!contacto.valor) {
            return;
        }

        const col = document.createElement('div');
        col.classList.add('col');

        col.innerHTML = `
            <div>
                <i class="bi ${contacto.icono} me-2"></i>

                ${contacto.link
                ? `<a href="${contacto.link}" target="_blank" class="text-dark">${contacto.valor}</a>`
                : `<span>${contacto.valor}</span>`
            }
            </div>
        `;

        contenedor.appendChild(col);
    });
}

function cargarMaterias(materias) {

    const contenedor = document.getElementById('contenedorMaterias');

    if (!materias || materias.length === 0) {
        return;
    }

    contenedor.innerHTML = `
        <h4 class="mb-3">Materias</h4>

        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4" id="materiasGrid"></div>
    `;

    const grid = document.getElementById('materiasGrid');

    materias.forEach(materia => {

        const col = document.createElement('div');
        col.classList.add('col');

        col.innerHTML = `
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body text-center fw-bold">
                    ${materia.nombre}
                </div>
            </div>
        `;

        grid.appendChild(col);
    });
}


function mostrarBotonEditar(info) {

    const acciones = document.getElementById('accionesPerfil');

    acciones.innerHTML = `
        <button class="btn btn-outline-primary"
                data-bs-toggle="offcanvas"
                data-bs-target="#editarPerfil">

            <i class="bi bi-pencil-square"></i>
            Editar

        </button>
    `;

    setValue('editNombre', info.nombre);
    setValue('editCarrera', info.carrera);
    setValue('editWhatsapp', info.telefono);
    setValue('editInstagram', info.instagram);
    setValue('editDiscord', info.discord);
    setValue('editTwitter', info.twitter);
    setValue('editFacebook', info.facebook);

    document.getElementById('guardarPerfilBtn').addEventListener('click', guardarPerfil);


}

async function guardarPerfil() {

    const datos = {
        nombre:
            document.getElementById('editNombre').value,
        carrera:
            document.getElementById('editCarrera').value,
        telefono:
            document.getElementById('editWhatsapp').value,
        instagram:
            document.getElementById('editInstagram').value,
        discord:
            document.getElementById('editDiscord').value,
        twitter:
            document.getElementById('editTwitter').value,
        facebook:
            document.getElementById('editFacebook').value
    };

    const respuesta = await fetch('/cositas/Asesorias/resources/php/editarPerfil.php',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        }
    );

    const resultado = await respuesta.json();

    if (resultado.mensaje === 'No autenticado') {
        alert('Se acabo el tiempo de sesión. Por favor, inicia sesión de nuevo.');
        window.location.href = '/cositas/Asesorias/bienvenida/';
    }

    console.log(resultado);

    const inputFoto =
        document.getElementById('inputFoto');

    const archivo =
        inputFoto.files[0];

    if (archivo) {
        await subirFotoPerfil(archivo);
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

async function subirFotoPerfil(archivo) {

    if (!archivo) {
        return;
    }

    const formData = new FormData();

    formData.append('foto', archivo);

    try {

        const respuesta = await fetch(
            '/cositas/Asesorias/resources/php/subirFoto.php',
            {
                method: 'POST',
                body: formData
            }
        );

        const datos = await respuesta.json();

        console.log(datos);

        if (datos.mensaje === 'OK') {

            const imagen =
                document.getElementById('imagenPerfil');

            imagen.src =
                datos.ruta + '?t=' + new Date().getTime();

        }

    } catch (error) {

        console.log(error);

    }
}

// esto se hace porque sino mostrarPerfilEditar explota cuando no hay algun input ;-; que basura
function setValue(id, valor) {

    const elemento =
        document.getElementById(id);

    if (elemento) {

        elemento.value = valor || '';

    }
}