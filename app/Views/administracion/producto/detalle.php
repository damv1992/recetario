<?= $this->extend('plantilla/index') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
    <div class="col-lg-12">
        <div class="nk-store-product">
            <div class="row vertical-gap">
                <div class="col-md-6">
                    <div class="nk-popup-gallery" data-pswp-uid="1">
                        <div class="nk-gallery-item-box">
                            <a href="<?=$producto['FotoProducto']?>" class="nk-gallery-item" data-size="554x554">
                                <div class="nk-gallery-item-overlay"><span class="ion-eye"></span></div>
                                <img src="<?=$producto['FotoProducto']?>">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="nk-product-title h3">
                        <?=$producto['NombreProducto']?> - <?=$plataforma['NombrePlataforma']?>
                    </h2>
                    <div class="nk-product-description">
                        <?=$producto['Descripcion']?>
                    </div>
                    <div class="nk-gap-2"></div>
                    <form action="#" class="nk-product-addtocart" novalidate="novalidate">
                        <div class="nk-product-price">Bs. <?=$producto['Precio']?></div>
                        <div class="nk-gap-1"></div>
                        <div class="input-group">
                            <button class="nk-btn nk-btn-rounded nk-btn-color-main-1">Añadir al carrito</button>
                        </div>
                    </form>
                    <div class="nk-gap-3"></div>
                    <div class="nk-product-meta">
                        <div>
                            <strong>Generos</strong>:
                            <?php foreach ($generos as $genero) { ?>
                                <a href="#"><?=$genero['NombreGenero']?></a>,
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>