<?php
    use App\Models\GenerosAlquilerModel;
    use App\Models\GenerosModel;
    use App\Models\PlataformaModel;

    $generosAlquileres = new GenerosAlquilerModel();
    $generos = new GenerosModel();
    $plataformas = new PlataformaModel();
    $mensajeAlquiler = 'Saludos, solicito alquilar la cuenta Play Station.';
    $mensajeCompra = 'Saludos, solicito comprar la cuenta Play Station por porcentajes.';
    $mensajeCompleto = 'Saludos, solicito comprar la cuenta Play Station por completo.';
    $mensajeAlquiler = str_replace(' ', '%20', $mensajeAlquiler);
    $mensajeCompra = str_replace(' ', '%20', $mensajeCompra);
    $mensajeCompleto = str_replace(' ', '%20', $mensajeCompleto);
?>
<?= $this->extend('plantilla/index') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
    <h3 class="nk-decorated-h-2">
        <span><span class="text-main-1">Juegos valorados por</span> <?=round($total, 2)?> Bs.</span>
    </h3>
    <h4 class="text-white">
        Para solicitar el alquiler de la cuenta a <span class="text-main-1">50 Bs/mes</span>
        haz clic <a href="https://api.whatsapp.com/send?phone=59173354006&text=<?=$mensajeAlquiler?>">aquí</a>.
    </h4>
    <h4 class="text-white">
        Para comprar la cuenta por porcentajes de 35% (para siempre) a <span class="text-main-1"><?=round($total*0.35)?> Bs.</span>
        haz clic <a href="https://api.whatsapp.com/send?phone=59173354006&text=<?=$mensajeCompra?>">aquí</a>.
    </h4>
    <h4 class="text-white">
        Para comprar la cuenta completa (para siempre) a <span class="text-main-1"><?=round($total)?> Bs.</span>
        haz clic <a href="https://api.whatsapp.com/send?phone=59173354006&text=<?=$mensajeCompleto?>">aquí</a>.
    </h4>
    <div class="nk-blog-grid">
        <div class="row">
            <?php foreach ($alquileres as $alquiler) {
                $generosAlquiler = $generosAlquileres->where('Producto', $alquiler['CodigoProducto'])->findAll();
                $plataforma = $plataformas->where('CodigoPlataforma', $alquiler['Plataforma'])->first();
                $enlace = site_url('alquiler/detalle/'.$alquiler['CodigoProducto']);
            ?>
                <div class="col-md-6 col-lg-3">
                    <div class="nk-blog-post">
                        <a href="<?=$enlace?>" class="nk-post-img">
                            <img src="<?=$alquiler['FotoProducto']?>">
                            <span class="nk-post-comments-count"><?=$plataforma['NombrePlataforma']?></span>
                            <span class="nk-post-categories">
                                <?php foreach ($generosAlquiler as $generoAlquiler) {
                                    $genero = $generos->where('CodigoGenero', $generoAlquiler['Genero'])->first();
                                ?>
                                    <span class="bg-main-5"><?=$genero['NombreGenero']?></span>
                                <?php } ?>
                            </span>

                        </a>
                        <div class="nk-gap"></div>
                        <h2 class="nk-post-title h4"><a href="<?=$enlace?>"><?=$alquiler['NombreProducto']?></a></h2>
                        <div class="nk-post-text"><!--<?=$alquiler['Descripcion']?>--></div>
                        <div class="nk-gap"></div>
                        <a href="<?=$enlace?>"
                            class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Leer Más</a>
                        <div class="nk-post-date float-right"><?=round($alquiler['Precio'], 2)?> Bs.</div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?= $this->endSection() ?>