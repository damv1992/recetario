var site_url = $('.site_url').val();
$(".text-main-1").not(".publicidades").removeClass("text-main-1");
$(".publicidades").addClass("text-main-1");

$(".tablaPublicidades").DataTable({
    ajax: {
        method: "POST",
        dataType: 'json',
        cache: false,
        url: site_url+'/publicidad/listar'
    },
    "pageLength" : 5,
    "responsive": true,
    "retrieve": true,
    "processing": true,
    "deferRender": false,
    "language": {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
});

$('#idPublicidad').hide();
$('.verImagen').hide();
if ($('#idPublicidad').val()) $('.verImagen').show();
$('.imagenPublicidad').change(function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.verImagen').show();
            $('.verImagen').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    } else $('.verImagen').hide();
});

$('.btnGuardarPublicidad').click(function () {
    let id = $('#idPublicidad').val();
    let titular = $('.titularPublicidad').val();
    let descripcion = CKEDITOR.instances.descripcionPublicidad.getData();
    let imagen = $('.imagenPublicidad').prop('files')[0];
    let enlace = $('.enlacePublicidad').val();
    let inicio = $('.fechaInicioPublicidad').val();
    let fin = $('.fechaFinPublicidad').val();
    let datos = new FormData();
    datos.append('id', id);
    datos.append('titular', titular);
    datos.append('descripcion', descripcion);
    datos.append('imagen', imagen);
    datos.append('enlace', enlace);
    datos.append('inicio', inicio);
    datos.append('fin', fin);
    $.ajax({
        url: site_url+'/publicidad/validar',
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(json) {
            $('.text-danger').remove();
            if (json.contador > 0) {
                if ((json.campos) && (json.mensajes)) {
                    var camposs = json.campos.slice(0, -1).split(',');
                    var mensajess = json.mensajes.slice(0, -1).split(',');
                    for (var i = 0; i < camposs.length; i++) {
                        var elemento = $('.'+camposs[i]);
                        $(elemento).parent().append('<div class="text-danger">'+mensajess[i]+'</div>');
                    }
                }
				$('.text-danger').parent().parent().addClass('has-error');
            } else {
                $('.alert-dismissible, .text-success').remove();
                $('.alert-dismissible, .text-danger').remove();
                $('.form-group').removeClass('has-error');
                $.ajax({
                    url: site_url+'/publicidad/guardar',
                    method: 'post',
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(respuesta) {
                        let mensaje;
                        if (respuesta === 'success') mensaje = 'Registro guardado correctamente.';
                        else mensaje = 'Algo falló al guardar el registro.';
                        $('.mensajePublicidad').append(
                            '<div class="nk-info-box text-' + respuesta + '">'+ mensaje +
                            '<div class="nk-info-box-close nk-info-box-close-btn"><i class="ion-close-round"></i></div></div>');
                        if (respuesta === 'success')
                            setTimeout(function() {
                                location.href = site_url+'/publicidad';
                            }, 3000);
                    }
                });
            }
        }
    });
});

$(".tablaPublicidades tbody").on("click", ".btnCambiar", function () {
    let objectBtn = $(this);
    let id = objectBtn.attr("codigo");
    let estado = objectBtn.attr("estado");º 
    let datos = new FormData();
    datos.append("id", id);
    datos.append("estado", estado);
    $.ajax({
        url: site_url+"/publicidad/estado",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta === "ok") {
                if (estado == 1) {
                    objectBtn.removeClass('btn-success');
                    objectBtn.addClass('btn-danger');
                    objectBtn.html('INACTIVO');
                    objectBtn.attr('estado', 0);
                } else {
                    objectBtn.addClass('btn-success');
                    objectBtn.removeClass('btn-danger');
                    objectBtn.html('ACTIVO');
                    objectBtn.attr('estado', 1);
                }
            }
        }
    });
});

$(".tablaPublicidades tbody").on("click", ".btnBorrarPublicidad", function () {
    let id = $(this).attr("codigo");
    let datos = new FormData();
    datos.append("id", id);
    Swal.fire({
        icon: "warning",
        title: "Confirmar borrado",
        text: "¿Está seguro de borrar el registro?",
        showCancelButton: true,
        confirmButtonText: 'Borrar',
        cancelButtonText: 'Cancelar'
    }).then(function (resultado) {
        if (resultado.value) {
            $.ajax({
                url: site_url+"/publicidad/borrar",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (respuesta) {
                    if (respuesta === "ok") {
                        Swal.fire({
                            icon: "success",
                            title: "Completado",
                            text: "El registro ha sido borrado correctamente.",
                            showCancelButton: false,
                            confirmButtonText: "Cerrar"
                        }).then(function () {
                            $(".tablaPublicidades").DataTable().ajax.reload();
                        });
                    } else {
                        let mensaje;
                        if (respuesta === "uso") mensaje = "No se puede borrar el registro, ya que esta en uso actualmente.";
                        else mensaje = "Ocurrio un error al borrar el registro con código "+id+"."
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: mensaje,
                            showCancelButton: false,
                            confirmButtonText: "Cerrar"
                        });
                    }
                }
            });
        }
    });
});