var site_url = $('.site_url').val();

function añadirCarrito(codigo) {
	$.ajax({
        url: site_url+"/catalogo/agregarCarrito",
        method: "POST",
        dataType: "json",
        data: {
            codigo: codigo
        },
        success: function(json) {
			mostrarCarrito();
		}
    });
}

function mostrarCarrito() {
	$.ajax({
		url: site_url+"/catalogo/carritoInfo",
		method: "POST",
		dataType: "json",
		data: {},
		success: function(json) {
			$('#carrito').html('');
			$('#carrito').html(json.html);
		}
	});
}

function retirarCarrito(codigo) {
    $.ajax({
        url: site_url+"/catalogo/retirarCarrito",
        method: "POST",
        dataType: "json",
        data: {
            codigo: codigo
        },
        success: function(json) {
			mostrarCarrito();
		}
    });
}

/* CARGAR LA TABLA DINÁMICA DE DESEOS */
$("#tablaCarrito").DataTable({
    ajax: {
        method: "POST",
        dataType: 'json',
        cache: false,
        url: base_url+'/index.php/producto/ListarCarritoAjax',
        data: {}
    },
    "pageLength": 50,
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

function actualizarCarrito(elemento) {
    let codigo = $(elemento).attr("codigo");
    let cantidad = $(elemento).val();
	let datos = new FormData();
	datos.append("codigoeditar", codigo);
	datos.append("cantidadeditar", cantidad);
	$.ajax({
		url: base_url+"/index.php/producto/ActualizarCarritoAjax",
		method: "POST",
		data: datos,
		cache: false,
		contentType: false,
		processData: false,
		success: function (respuesta) {
			if (respuesta === "ok") $("#tablaCarrito").DataTable().ajax.reload();
			// Muestra los productos al desplegar carrito
			var carrito = base_url+"/index.php/producto/carritoInfo";
			$('#cart > ul').load(carrito+' ul li');
		}
	});
}

/* AÑADIR DESEO */
$("#tablaCarrito tbody").on("click", ".btnAñadirDeseo", function () {
    let codigo = $(this).attr("codigo");
    let datos = new FormData();
    datos.append("producto", codigo);
	$.ajax({
        url: base_url+"/index.php/producto/AñadirDeseoAjax",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            if (respuesta === "ok") {
				$("#tablaCarrito").DataTable().ajax.reload();
            } else {
                let mensaje;
                if (respuesta === "sesion") mensaje = "Sesion caducada. Vuelva a iniciar sesion.";
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showCancelButton: false,
                    confirmButtonColor: "#337ab7",
                    confirmButtonText: "Cerrar"
                });
            }
        }
    });
});

/* RETIRAR DE LA LISTA DE DESEOS */
$("#tablaCarrito tbody").on("click", ".btnRetirarDeseo", function () {
    let codigo = $(this).attr("codigo");
    let datos = new FormData();
    datos.append("producto", codigo);
    Swal.fire({
        icon: "warning",
        title: "Confirmación retiro de deseado",
        text: "¿Está seguro de retirar el producto de la lista de deseos?",
        showCancelButton: true,
        confirmButtonColor: "#337ab7",
        confirmButtonText: 'Retirar',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar'
    }).then(function (resultado) {
        if (resultado.value) {
            $.ajax({
                url: base_url+"/index.php/producto/RetirarDeseoAjax",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function (respuesta) {
                    if (respuesta === "ok") {
                        Swal.fire({
                            icon: "success",
                            title: "Retiro Completado",
                            text: "El producto se retiro de la lista de deseoss.",
                            showCancelButton: false,
                            confirmButtonColor: "#337ab7",
                            confirmButtonText: "Cerrar"
                        }).then(function () {
							$("#tablaCarrito").DataTable().ajax.reload();
                        });
                    } else {
                        let mensaje;
                        if (respuesta === "sesion") mensaje = "Sesion caducada. Vuelva a iniciar sesion.";
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: mensaje,
                            showCancelButton: false,
                            confirmButtonColor: "#337ab7",
                            confirmButtonText: "Cerrar"
                        });
                    }
                }
            });
        }
    });
});

/* RETIRAR DEL CARRITO */
$("#tablaCarrito tbody").on("click", ".btnRetirarCarrito", function () {
    let codigo = $(this).attr("codigo");
    retirarCarrito(codigo);
	$("#tablaCarrito").DataTable().ajax.reload();
});

// Cart add remove functions
/*var cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(json) {
				$('.alert-dismissible, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {

					$('#cart > button').html('<span class="cart-link"><span class="cart-img hidden-xs hidden-sm"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="shopping-bag" viewBox="0 0 860 860"><title>shopping-bag</title><path d="M434.979,42.667H85.333v3.061l-4.375-14.999C75.687,12.635,58.844,0,40,0H10.667C4.771,0,0,4.771,0,10.667s4.771,10.667,10.667,10.667H40c9.427,0,17.844,6.313,20.479,15.365l66.396,227.635l-34.021,42.521c-4.854,6.073-7.521,13.688-7.521,21.458c0,18.948,15.406,34.354,34.354,34.354H416c5.896,0,10.667-4.771,10.667-10.667c0-5.896-4.771-10.667-10.667-10.667H119.687c-7.177,0-13.021-5.844-13.021-13.021c0-2.948,1.01-5.844,2.854-8.135l34.279-42.844h209.221c16.448,0,31.604-9.615,38.615-24.5l74.438-158.177c2.135-4.552,3.26-9.604,3.26-14.615v-3.021C469.333,58.073,453.927,42.667,434.979,42.667z M448,80.042c0,1.906-0.427,3.823-1.24,5.542L372.333,243.75c-3.51,7.438-11.083,12.25-19.313,12.25H146.667L90.663,64h344.316C442.156,64,448,69.844,448,77.021V80.042z"/><path d="M128,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S151.531,384,128,384z M128,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C149.333,438.427,139.76,448,128,448z"/><path d="M384,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S407.531,384,384,384z M384,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C405.333,438.427,395.76,448,384,448z"/></symbol></svg><svg class="icon" viewBox="0 0 30 30"><use xlink:href="#shopping-bag" x="19%" y="7%"></use></svg></span><span class="cart-img hidden-lg hidden-md"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="cart-responsive" viewBox="0 0 510 510"><title>cart-responsive</title><path d="M306.4,313.2l-24-223.6c-0.4-3.6-3.6-6.4-7.2-6.4h-44.4V69.6c0-38.4-31.2-69.6-69.6-69.6c-38.4,0-69.6,31.2-69.6,69.6v13.6H46c-3.6,0-6.8,2.8-7.2,6.4l-24,223.6c-0.4,2,0.4,4,1.6,5.6c1.2,1.6,3.2,2.4,5.2,2.4h278c2,0,4-0.8,5.2-2.4C306,317.2,306.8,315.2,306.4,313.2z M223.6,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C217.2,126.4,220,123.6,223.6,123.6z M106,69.6c0-30.4,24.8-55.2,55.2-55.2c30.4,0,55.2,24.8,55.2,55.2v13.6H106V69.6zM98.8,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C92.4,126.4,95.2,123.6,98.8,123.6z M30,306.4L52.4,97.2h39.2v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2h110.4v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2H270l22.4,209.2H30z"></path></symbol></svg><svg class="icon" viewBox="0 0 40 40"><use xlink:href="#cart-responsive" x="13%" y="13%"></use></svg></span><span class="cart-content"><span class="cart-products-count hidden-sm hidden-xs">' + json['text_items_small'] + ' ' + json['total'] + '</span><span class="cart-products-count hidden-lg hidden-md">' + json['text_items_small'] + '</span></span></span>');						
						}, 100);

					$.notify({message:json.success},{type:"success",offset:0,placement:{from:"top",align:"center"},z_index: 99999999,animate:{enter:"animated fadeInDown",exit:"animated fadeOutUp"},template:'<div data-notify="container" class="col-xs-12 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'});
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span class="cart-link"><span class="cart-img hidden-xs hidden-sm"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="shopping-bag" viewBox="0 0 860 860"><title>shopping-bag</title><path d="M434.979,42.667H85.333v3.061l-4.375-14.999C75.687,12.635,58.844,0,40,0H10.667C4.771,0,0,4.771,0,10.667s4.771,10.667,10.667,10.667H40c9.427,0,17.844,6.313,20.479,15.365l66.396,227.635l-34.021,42.521c-4.854,6.073-7.521,13.688-7.521,21.458c0,18.948,15.406,34.354,34.354,34.354H416c5.896,0,10.667-4.771,10.667-10.667c0-5.896-4.771-10.667-10.667-10.667H119.687c-7.177,0-13.021-5.844-13.021-13.021c0-2.948,1.01-5.844,2.854-8.135l34.279-42.844h209.221c16.448,0,31.604-9.615,38.615-24.5l74.438-158.177c2.135-4.552,3.26-9.604,3.26-14.615v-3.021C469.333,58.073,453.927,42.667,434.979,42.667z M448,80.042c0,1.906-0.427,3.823-1.24,5.542L372.333,243.75c-3.51,7.438-11.083,12.25-19.313,12.25H146.667L90.663,64h344.316C442.156,64,448,69.844,448,77.021V80.042z"/><path d="M128,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S151.531,384,128,384z M128,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C149.333,438.427,139.76,448,128,448z"/><path d="M384,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S407.531,384,384,384z M384,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C405.333,438.427,395.76,448,384,448z"/></symbol></svg><svg class="icon" viewBox="0 0 30 30"><use xlink:href="#shopping-bag" x="19%" y="7%"></use></svg></span><span class="cart-img hidden-lg hidden-md"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="cart-responsive" viewBox="0 0 510 510"><title>cart-responsive</title><path d="M306.4,313.2l-24-223.6c-0.4-3.6-3.6-6.4-7.2-6.4h-44.4V69.6c0-38.4-31.2-69.6-69.6-69.6c-38.4,0-69.6,31.2-69.6,69.6v13.6H46c-3.6,0-6.8,2.8-7.2,6.4l-24,223.6c-0.4,2,0.4,4,1.6,5.6c1.2,1.6,3.2,2.4,5.2,2.4h278c2,0,4-0.8,5.2-2.4C306,317.2,306.8,315.2,306.4,313.2z M223.6,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C217.2,126.4,220,123.6,223.6,123.6z M106,69.6c0-30.4,24.8-55.2,55.2-55.2c30.4,0,55.2,24.8,55.2,55.2v13.6H106V69.6zM98.8,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C92.4,126.4,95.2,123.6,98.8,123.6z M30,306.4L52.4,97.2h39.2v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2h110.4v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2H270l22.4,209.2H30z"></path></symbol></svg><svg class="icon" viewBox="0 0 40 40"><use xlink:href="#cart-responsive" x="13%" y="13%"></use></svg></span><span class="cart-content"><span class="cart-products-count hidden-sm hidden-xs">' + json['text_items_small'] + ' ' + json['total'] + '</span><span class="cart-products-count hidden-lg hidden-md">' + json['text_items_small'] + '</span></span></span>');						
						}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span class="cart-link"><span class="cart-img hidden-xs hidden-sm"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="shopping-bag" viewBox="0 0 860 860"><title>shopping-bag</title><path d="M434.979,42.667H85.333v3.061l-4.375-14.999C75.687,12.635,58.844,0,40,0H10.667C4.771,0,0,4.771,0,10.667s4.771,10.667,10.667,10.667H40c9.427,0,17.844,6.313,20.479,15.365l66.396,227.635l-34.021,42.521c-4.854,6.073-7.521,13.688-7.521,21.458c0,18.948,15.406,34.354,34.354,34.354H416c5.896,0,10.667-4.771,10.667-10.667c0-5.896-4.771-10.667-10.667-10.667H119.687c-7.177,0-13.021-5.844-13.021-13.021c0-2.948,1.01-5.844,2.854-8.135l34.279-42.844h209.221c16.448,0,31.604-9.615,38.615-24.5l74.438-158.177c2.135-4.552,3.26-9.604,3.26-14.615v-3.021C469.333,58.073,453.927,42.667,434.979,42.667z M448,80.042c0,1.906-0.427,3.823-1.24,5.542L372.333,243.75c-3.51,7.438-11.083,12.25-19.313,12.25H146.667L90.663,64h344.316C442.156,64,448,69.844,448,77.021V80.042z"/><path d="M128,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S151.531,384,128,384z M128,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C149.333,438.427,139.76,448,128,448z"/><path d="M384,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S407.531,384,384,384z M384,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C405.333,438.427,395.76,448,384,448z"/></symbol></svg><svg class="icon" viewBox="0 0 30 30"><use xlink:href="#shopping-bag" x="19%" y="7%"></use></svg></span><span class="cart-img hidden-lg hidden-md"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="cart-responsive" viewBox="0 0 510 510"><title>cart-responsive</title><path d="M306.4,313.2l-24-223.6c-0.4-3.6-3.6-6.4-7.2-6.4h-44.4V69.6c0-38.4-31.2-69.6-69.6-69.6c-38.4,0-69.6,31.2-69.6,69.6v13.6H46c-3.6,0-6.8,2.8-7.2,6.4l-24,223.6c-0.4,2,0.4,4,1.6,5.6c1.2,1.6,3.2,2.4,5.2,2.4h278c2,0,4-0.8,5.2-2.4C306,317.2,306.8,315.2,306.4,313.2z M223.6,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C217.2,126.4,220,123.6,223.6,123.6z M106,69.6c0-30.4,24.8-55.2,55.2-55.2c30.4,0,55.2,24.8,55.2,55.2v13.6H106V69.6zM98.8,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C92.4,126.4,95.2,123.6,98.8,123.6z M30,306.4L52.4,97.2h39.2v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2h110.4v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2H270l22.4,209.2H30z"></path></symbol></svg><svg class="icon" viewBox="0 0 40 40"><use xlink:href="#cart-responsive" x="13%" y="13%"></use></svg></span><span class="cart-content"><span class="cart-products-count hidden-sm hidden-xs">' + json['text_items_small'] + ' ' + json['total'] + '</span><span class="cart-products-count hidden-lg hidden-md">' + json['text_items_small'] + '</span></span></span>');						
						}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span class="cart-link"><span class="cart-img hidden-xs hidden-sm"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="shopping-bag" viewBox="0 0 860 860"><title>shopping-bag</title><path d="M434.979,42.667H85.333v3.061l-4.375-14.999C75.687,12.635,58.844,0,40,0H10.667C4.771,0,0,4.771,0,10.667s4.771,10.667,10.667,10.667H40c9.427,0,17.844,6.313,20.479,15.365l66.396,227.635l-34.021,42.521c-4.854,6.073-7.521,13.688-7.521,21.458c0,18.948,15.406,34.354,34.354,34.354H416c5.896,0,10.667-4.771,10.667-10.667c0-5.896-4.771-10.667-10.667-10.667H119.687c-7.177,0-13.021-5.844-13.021-13.021c0-2.948,1.01-5.844,2.854-8.135l34.279-42.844h209.221c16.448,0,31.604-9.615,38.615-24.5l74.438-158.177c2.135-4.552,3.26-9.604,3.26-14.615v-3.021C469.333,58.073,453.927,42.667,434.979,42.667z M448,80.042c0,1.906-0.427,3.823-1.24,5.542L372.333,243.75c-3.51,7.438-11.083,12.25-19.313,12.25H146.667L90.663,64h344.316C442.156,64,448,69.844,448,77.021V80.042z"/><path d="M128,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S151.531,384,128,384z M128,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C149.333,438.427,139.76,448,128,448z"/><path d="M384,384c-23.531,0-42.667,19.135-42.667,42.667s19.135,42.667,42.667,42.667s42.667-19.135,42.667-42.667S407.531,384,384,384z M384,448c-11.76,0-21.333-9.573-21.333-21.333c0-11.76,9.573-21.333,21.333-21.333c11.76,0,21.333,9.573,21.333,21.333C405.333,438.427,395.76,448,384,448z"/></symbol></svg><svg class="icon" viewBox="0 0 30 30"><use xlink:href="#shopping-bag" x="19%" y="7%"></use></svg></span><span class="cart-img hidden-lg hidden-md"><svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="cart-responsive" viewBox="0 0 510 510"><title>cart-responsive</title><path d="M306.4,313.2l-24-223.6c-0.4-3.6-3.6-6.4-7.2-6.4h-44.4V69.6c0-38.4-31.2-69.6-69.6-69.6c-38.4,0-69.6,31.2-69.6,69.6v13.6H46c-3.6,0-6.8,2.8-7.2,6.4l-24,223.6c-0.4,2,0.4,4,1.6,5.6c1.2,1.6,3.2,2.4,5.2,2.4h278c2,0,4-0.8,5.2-2.4C306,317.2,306.8,315.2,306.4,313.2z M223.6,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C217.2,126.4,220,123.6,223.6,123.6z M106,69.6c0-30.4,24.8-55.2,55.2-55.2c30.4,0,55.2,24.8,55.2,55.2v13.6H106V69.6zM98.8,123.6c3.6,0,6.4,2.8,6.4,6.4c0,3.6-2.8,6.4-6.4,6.4c-3.6,0-6.4-2.8-6.4-6.4C92.4,126.4,95.2,123.6,98.8,123.6z M30,306.4L52.4,97.2h39.2v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2h110.4v13.2c-8,2.8-13.6,10.4-13.6,19.2c0,11.2,9.2,20.4,20.4,20.4c11.2,0,20.4-9.2,20.4-20.4c0-8.8-5.6-16.4-13.6-19.2V97.2H270l22.4,209.2H30z"></path></symbol></svg><svg class="icon" viewBox="0 0 40 40"><use xlink:href="#cart-responsive" x="13%" y="13%"></use></svg></span><span class="cart-content"><span class="cart-products-count hidden-sm hidden-xs">' + json['text_items_small'] + ' ' + json['total'] + '</span><span class="cart-products-count hidden-lg hidden-md">' + json['text_items_small'] + '</span></span></span>');						
						}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}*/