window.addEventListener("load", function(){

    var boton_pedir = document.getElementById('pedir');
	boton_pedir.addEventListener("click", () => {
        let correo = document.getElementById("correo").value;
        let url = "/cositas/Asesorias/peticionDB.php";
        let datos = {
			correo: correo
		}

        fetch(url,{
			method:'POST',
			body: JSON.stringify(datos),
			headers: {
				//'Content-Type': 'text/plain;charset=UTF-8'
				'Content-Type': 'application/json'
			}
		})
		.then(respuesta=>{
			//return respuesta.text();
			return respuesta.json();
		})
		.then(datos=>{
            console.log(datos.mensaje);
			console.log(datos.error);
        })
        .catch(error => {
            console.log(error);
        });
    });

});