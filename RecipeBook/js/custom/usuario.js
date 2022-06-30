var site_url = $(".site_url").val();

$(".btnIniciarSesion").click(function () {
    let username = $(".txtUsuario").val();
    let password = $(".txtContraseña").val();
    let datos = new FormData();
    datos.append("username", username);
    datos.append("password", password);
    $.ajax({
        url: site_url + "/administrador/login",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta === "ok") location.reload();
            else {
                let mensaje;
                if (respuesta === "no_existe") mensaje = "Usuario incorrecto.";
                else if (respuesta === "incorrecto") mensaje = "Contraseña incorrecta.";
                else mensaje = "Ocurrio un error. Comuniquese con el administrador del sistema.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Cerrar"
                });
            }
        }
    });
});