var site_url = $('.site_url').val();
filtrarProductos();

$('#precioMax').change(function () {
    filtrarProductos();
});

$('.txtOrden').change(function () {
    filtrarProductos();
});

function filtrarProductos() {
    let busqueda = $('.criterioBusqueda').val();
    let precioMin = $('.nk-input-slider-value-0').parent().children("span").html();
    let precioMax = $('.nk-input-slider-value-1').parent().children("span").html();
    let orden = $('.txtOrden').val();
    let limite = $('#txtLimite').val();
    let pagina = $('#txtPagina').val();
    let plataforma = $('#txtPlataforma').val();
    let tipo = $('#txtTipo').val();
    let genero = $('#txtGenero').val();

    let plat = $('#auxPlataforma').val();
    if (plat) {
        plataforma = $('#auxPlataforma').val();
        $('#auxPlataforma').val("");
    }

    /*let categoria;
    if ((!cate) && (!cat)) categoria = $('#txtCategoria').val();
    else if (cate) { categoria = $('#auxCategoria').val(); $('#auxCategoria').val(""); }
    else { categoria = $('#auxCat').val(); $('#auxCat').val(""); }*/
    if (!pagina) pagina = 1;
    $.ajax({
        url: site_url + "/catalogo/filtrarProductos",
        method: "POST",
        dataType: 'json',
        data: {
            busqueda: busqueda,
            precioMin: precioMin,
            precioMax: precioMax,
            orden: orden,
            limite: limite,
            pagina: pagina,
            plataforma: plataforma,
            tipo: tipo,
            genero: genero
        },
        success: function(data) {
            $('.botonesFiltroProductos').html(data.botonesFiltroProductos);
            if (data.contadorFiltroProductos == 0) $('.contadorFiltroProductos').html("No se encontraron coincidencias.");
            if (data.contadorFiltroProductos == 1) $('.contadorFiltroProductos').html("Se encontró <span>"+data.contadorFiltroProductos+"</span> coincidencia.");
            if (data.contadorFiltroProductos > 1) $('.contadorFiltroProductos').html("Se encontraron <span>"+data.contadorFiltroProductos+"</span> coincidencias.");
            $('.resultadosFiltroProductos').html(data.resultadosFiltroProductos);
            $('.paginasFiltroProductos').html(data.paginasFiltroProductos);
            $('#txtBusqueda').focus();
        }
    })
}

function limpiarTodo() {
    $('.criterioBusqueda').val("");
    $('#txtPlataforma').val("");
    $('#txtTipo').val("");
    $('#txtGenero').val("");
    $('.txtOrden').val("");
    $('#input-limit').val(20);
    filtrarProductos();
}

function plataforma(plataforma) {
    $('#txtPlataforma').val(plataforma);
    filtrarProductos();
}
function limpiarPlataforma() {
    $('#txtPlataforma').val("");
    filtrarProductos();
}

function tipo(tipo) {
    $('#txtTipo').val(tipo);
    filtrarProductos();
}
function limpiarTipo() {
    $('#txtTipo').val("");
    filtrarProductos();
}

function genero(genero) {
    $('#txtGenero').val(genero);
    filtrarProductos();
}
function limpiarGenero() {
    $('#txtGenero').val("");
    filtrarProductos();
}

function paginaProducto(boton, pagina) {
    $('#txtPagina').val(pagina);
    filtrarProductos();
}

function PaginaSiguienteProducto() {
    var siguiente = $('#txtPagina').val();
    siguiente += 10;
    $('#txtPagina').val(siguiente);
    filtrarProductos();
}

function PaginaAnteriorProducto() {
    var anterior = $('#txtPagina').val();
    anterior -= 10;
    $('#txtPagina').val(anterior);
    filtrarProductos();
}