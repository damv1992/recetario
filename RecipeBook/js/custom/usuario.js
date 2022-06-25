var site_url = $(".site_url").val();

/*$("#btnCrearUsuario").click(function () {
    let email = $("#emailNew").val();
    let password = $("#passwordNew").val();
    let nombre = $("#nombreNew").val();
    let direccion = $("#direccionNew").val();
    let telefono = $("#telefonoNew").val();
    let empresa = $("#empresaNew").val();
    let pagina = $("#paginaNew").val();
    let rol = $("#rolNew").val();
    let datos = new FormData();
    datos.append("emailcrear", email);
    datos.append("passwordcrear", password);
    datos.append("nombrecrear", nombre);
    datos.append("direccioncrear", direccion);
    datos.append("telefonocrear", telefono);
    datos.append("empresacrear", empresa);
    datos.append("paginacrear", pagina);
    datos.append("rolcrear", rol);
    $.ajax({
        url: base_url+"/index.php/UsuariosController/CrearUsuarioAjax",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta === "ok") {
                $("#modalCrearUsuario").modal('hide');
                $("#modalLoginUsuario").modal('hide');
                Swal.fire({
                    icon: "success",
                    title: "Creación Completada",
                    text: "Enviamos un enlace de confirmación de cuenta al correo " + email + ".",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                }).then(function () {
                    //acciones
                });
            }
            else {
                let mensaje;
                if (respuesta === "existe") mensaje = "El correo " + email + " ya existe. Ingrese otro email.";
                else if (respuesta === "direccion") mensaje = "Debe introducir una dirección";
                else if (respuesta === "telefono") mensaje = "Debe introducir el teléfono";
                else if (respuesta === "rol") mensaje = "Debe elegir un rol de usuario";
                else if (respuesta === "fallo") mensaje = "Debe introducir una dirección de correo electrónico válida.";
                else mensaje = "Ocurrio un error al crear el usuario " + email + ". Comuniquese con el administrador del sistema.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Cerrar"
                }).then(function () {
                    //acciones
                });
            }
        }
    });
});*/

$(".btnLogin").click(function () {
    let username = $(".userLogin").val();
    let password = $(".contrasenaLogin").val();
    let datos = new FormData();
    datos.append("username", username);
    datos.append("password", password);
    $.ajax({
        url: site_url+"/Usuario/LoginUsuarioAjax",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta === "ok") {
                $("#modalLoginUsuario").modal('hide');
                location.reload();
            }
            else {
                let mensaje;
                if (respuesta === "no_existe") {
                    mensaje = "La cuenta con email: " + email + " no existe. Regístrese al sistema.";
                    $("#modalLoginUsuario").modal('hide');
                    $("#modalCrearUsuario").modal('show');
                } else if (respuesta === "validar") mensaje = "Su cuenta aún no fue activada. Por favor revise su bandeja de entrada.";
                else if (respuesta === "incorrecto") mensaje = "Contraseña incorrecta.";
                else mensaje = "Ocurrio un error al logear el usuario " + email + ". Comuniquese con el administrador del sistema.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Cerrar"
                }).then(function () {
                    //acciones
                });
            }
        }
    });
});

/*$("a#linkActualizarUsuario").click(function () {
    $.ajax({
        url: base_url+"/index.php/UsuariosController/BuscarUsuarioAjax",
        method: "POST",
        data: {},
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (respuesta) {
            $("#emailUpd").html("<b>" + respuesta["Email"] + "</b>");
            $("#nombreUpd").val(respuesta["NombreCompleto"]);
            $("#direccionUpd").val(respuesta["Direccion"]);
            $("#telefonoUpd").val(respuesta["Telefono"]);
            $("#empresaUpd").val(respuesta["Empresa"]);
            $("#paginaUpd").val(respuesta["Pagina"]);
            $("#rolUpd").val(respuesta["RolAsignado"]);
        },
        error: function (respuesta) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Ocurrio un error al cargar los datos del usuario. Comuniquese con el administrador del sistema.",
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Cerrar'
            }).then(function () {
                $('#modalActualizarUsuario').modal('hide');
            });
        }
    });
});*/

/*$("#btnActualizarUsuario").click(function () {
    let password = $("#passwordUpd").val();
    let nombre = $("#nombreUpd").val();
    let direccion = $("#direccionUpd").val();
    let telefono = $("#telefonoUpd").val();
    let empresa = $("#empresaUpd").val();
    let pagina = $("#paginaUpd").val();
    let rol = $("#rolUpd").val();
    let datos = new FormData();
    datos.append("passwordactualizar", password);
    datos.append("nombreactualizar", nombre);
    datos.append("direccionactualizar", direccion);
    datos.append("telefonoactualizar", telefono);
    datos.append("empresaactualizar", empresa);
    datos.append("paginaactualizar", pagina);
    datos.append("rolactualizar", rol);
    $.ajax({
        url: base_url+"/index.php/UsuariosController/ActualizarUsuarioAjax",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta === "ok") {
                $("#modalActualizarUsuario").modal('hide');
                Swal.fire({
                    icon: "success",
                    title: "Actualización Completada",
                    text: "El usuario " + nombre + " ha sido guardado correctamente.",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Cerrar"
                }).then(function () {
                    location.reload();
                });
            }
            else {
                let mensaje;
                if (respuesta === "sesion") mensaje = "Sesión caducada. Vuelva a iniciar sesión.";
                else if (respuesta === "direccion") mensaje = "Debe introducir una dirección";
                else if (respuesta === "telefono") mensaje = "Debe introducir el teléfono";
                else if (respuesta === "rol") mensaje = "Debe elegir un rol de usuario";
                else mensaje = "Ocurrio un error al actualizar los datos del usuario. Comuniquese con el administrador del sistema.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Cerrar"
                }).then(function () {
                    //acciones
                });
            }
        }
    });
});*/

/*$("a#RecuperarContraseña").click(function () {
    let email = $("#emailLogin").val();
    //let email = $("#emailRC").val();
    let datos = new FormData();
    datos.append("emailRcñ", email);
    $.ajax({
        url: base_url+"/index.php/UsuariosController/OlvidoContraseñaAjax",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            $("#modalLoginUsuario").modal('hide');
            if (respuesta === "ok") {
                Swal.fire({
                    icon: "success",
                    title: "Recuperación de contraseña",
                    text: "Te hemos enviado tu contraseña a tu correo: " + email + ". Por favor comprueba tu bandeja de entrada.",
                    showCancelButton: false,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                });
            } else {
                let mensaje;
                if (respuesta === "vacio") mensaje = "Debe introducir un correo electrónico existente, para recuperar su contraseña";
                else if (respuesta === "no_registrado") mensaje = "No se encontró ninguna cuenta registrada con " + email + ". Debe introducir el correo electrónico que usó para registrarse";
                else if (respuesta === "fallo") mensaje = "Introduzca una dirección de correo electrónico válida.";
                else mensaje = "Ocurrio un error al enviar el enlace al correo: " + email + ". Comuníquese con el administrador del sistema.";
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
});*/