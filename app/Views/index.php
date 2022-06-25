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
    <!-- Publicidades -->
    <div class="nk-image-slider" data-autoplay="8000">
        <!-- Publicidad -->
        <?php foreach ($publicidades as $publicidad) { ?>
        <div class="nk-image-slider-item">
            <img src="<?=base_url().$publicidad['ImagenPublicidad']?>" class="nk-image-slider-img" data-thumb="<?=base_url().$publicidad['ImagenPublicidad']?>">
            <div class="nk-image-slider-content">
                <h3 class="h4"><?=$publicidad['Titular']?></h3>
                <div class="text-white text-justify"><?=$publicidad['Descripcion']?></div>
                <a href="<?=$publicidad['EnlacePublicidad']?>" target="_blank"
                    class="nk-btn nk-btn-rounded nk-btn-color-white nk-btn-hover-color-main-1">Leer Más</a>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Plataformas -->
    <div class="nk-gap-2"></div>
    <div class="row vertical-gap">
        <!-- Plataforma -->
        <?php foreach ($plataformas as $plataforma) { ?>
        <div class="col-lg-4">
            <div class="nk-feature-1">
                <div class="nk-feature-icon">
                    <img src="<?=$plataforma['IconoPlataforma']?>">
                </div>
                <div class="nk-feature-cont"><a href="<?=site_url('catalogo?plataforma='.$plataforma['CodigoPlataforma'])?>">
                    <h3 class="nk-feature-title"><?=$plataforma['NombrePlataforma']?></h3>
                    <?php if ($plataforma['NombrePlataforma'] == 'TV') { ?>
                        <h4 class="nk-feature-title text-main-1">Aplicaciones</h4>
                    <?php } else { ?>
                        <h4 class="nk-feature-title text-main-1">Ver Juegos</h4>
                    <?php } ?>
                </a></div>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="nk-gap-2"></div>
    <div class="nk-blog-grid">
        <div class="row">
            <?php foreach ($productos as $producto) {
                $generosProducto = $generosProductos->where('Producto', $producto['CodigoProducto'])->findAll();
                $plataforma = $plataformass->where('CodigoPlataforma', $producto['Plataforma'])->first();
                $enlace = site_url('producto/detalle/'.$producto['CodigoProducto']);
            ?>
                <div class="col-md-6 col-lg-3">
                    <div class="nk-blog-post">
                        <a href="<?=$enlace?>" class="nk-post-img">
                            <img src="<?=$producto['FotoProducto']?>">
                            <span class="nk-post-comments-count"><?=$plataforma['NombrePlataforma']?></span>
                            <span class="nk-post-categories">
                                <?php foreach ($generosProducto as $generoProducto) {
                                    $genero = $generos->where('CodigoGenero', $generoProducto['Genero'])->first();
                                ?>
                                    <span class="bg-main-5"><?=$genero['NombreGenero']?></span>
                                <?php } ?>
                            </span>

                        </a>
                        <div class="nk-gap"></div>
                        <h2 class="nk-post-title h4"><a href="<?=$enlace?>"><?=$producto['NombreProducto']?></a></h2>
                        <div class="nk-post-text">
                            <!--<?=$producto['Descripcion']?>-->
                            <div class="nk-product-price">Bs. <?=$producto['Precio']?></div>
                        </div>
                        <div class="nk-gap"></div>
                        <a href="<?=$enlace?>" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">
                            Leer Más
                        </a>
                        <a href="#" onclick="añadirCarrito(<?=$producto['CodigoProducto']?>);" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">
                            <i class="fas fa-cart-plus"></i>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <center>
            <a href="<?=site_url('catalogo')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-1">Ver más</a>
        </center>
    </div>
<?= $this->endSection() ?>