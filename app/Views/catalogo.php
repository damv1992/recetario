<?php
    use App\Models\GenerosProductoModel;
    use App\Models\GenerosModel;
    use App\Models\PlataformaModel;

    $generosProductos = new GenerosProductoModel();
    $generos = new GenerosModel();
    $plataformass = new PlataformaModel();
?>
<?= $this->extend('plantilla/index') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
    <input id="auxPlataforma" value="<?=$plataforma?>" type="hidden">
    <input id="txtBusqueda" value="<?=$busqueda?>" type="hidden">
    <div class="row vertical-gap">
        <div class="col-lg-4">
            <aside class="nk-sidebar nk-sidebar-right nk-sidebar-sticky">
                <div class="nk-widget">
                    <div class="nk-widget-content">
                        <form action="#" class="nk-form nk-form-style-1" novalidate="novalidate">
                            <div class="input-group">
                                <input type="text" class="form-control criterioBusqueda" placeholder="Buscar..." onkeyup="filtrarProductos();">
                                <button class="nk-btn nk-btn-color-main-1" onclick="limpiarTodo();">Limpiar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="nk-input-slider">
                    <input id="precioMax" type="text" name="price-filter" data-slider-min="0" data-slider-max="500"
                        data-slider-step="1" data-slider-value="[0, 500]" data-slider-tooltip="hide">
                    <div class="nk-gap"></div>
                    <div>
                        <div class="text-white mt-4 float-left">
                            PRECIO:
                            <strong class="text-main-1">Bs. <span class="nk-input-slider-value-0"></span></strong>
                            -
                            <strong class="text-main-1">Bs. <span class="nk-input-slider-value-1"></span></strong>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="botonesFiltroProductos"></div>

            </aside>
            <!-- END: Sidebar -->
        </div>

        <div class="col-lg-8">
            <div class="row vertical-gap">
                <div class="col-md-6">
                    <select class="form-control txtOrden">
                        <option value="">Ordenar por</option>
                        <option value="plataformas.NombrePlataforma">Plataforma</option>
                        <option value="tipos.NombreTipo">Tipo de producto</option>
                        <option value="generos.NombreProducto">Genero</option>
                    </select>
                </div>
            </div>

            <!-- START: Products -->
            <div class="nk-gap-3"></div>
            <div class="row vertical-gap resultadosFiltroProductos">
                <!--<div class="contadorFiltroProductos"></div>-->
            </div>
            <!-- END: Products -->

            <!-- START: Pagination -->
            <div class="nk-gap-3"></div>
            <div class="paginasFiltroProductos"></div>
            <!-- END: Pagination -->
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?=base_url()?>/GoodGames/assets/js/custom/filtro.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/js/custom/carrito.js"></script>
<?= $this->endSection() ?>