<?= $this->extend('plantilla/index') ?>

<?= $this->section('contenido') ?>
<div class="container">
    <div class="nk-gap-4"></div>
    <div class="row vertical-gap">
        <div class="col-lg-8 order-lg-2">
            <?= $this->renderSection('contenidoMenu') ?>
        </div>
        <?php // class="text-main-1" ?>
        <div class="col-lg-4">
            <aside class="nk-sidebar nk-sidebar-left nk-sidebar-sticky">
                <div class="nk-widget nk-widget-highlighted">
                    <h4 class="nk-widget-title"><span><span class="text-main-1">Menu</span> de administración</span></h4>
                    <div class="nk-widget-content">
                        <ul class="nk-widget-categories">
                            <li><a class="pagina" href="<?=site_url('administracion/pagina')?>">Configuración</a></li>
                            <li><a class="sociales" href="<?=site_url('social')?>">Redes Sociales</a></li>
                            <li><a class="pagos" href="<?=site_url('pago')?>">Métodos de Pago</a></li>
                            <li><a class="publicidades" href="<?=site_url('publicidad')?>">Publicidades</a></li>
                            <li><a class="plataformas" href="<?=site_url('plataforma')?>">Plataformas</a></li>
                            <li><a class="tipos" href="<?=site_url('tipo')?>">Tipos</a></li>
                            <li><a class="generos" href="<?=site_url('genero')?>">Géneros</a></li>
                            <li><a class="productos" href="<?=site_url('producto')?>">Productos</a></li>
                            <li><a class="alquileres" href="<?=site_url('alquiler')?>">Alquiler</a></li>
                            <li><a class="usuarios" href="<?=site_url('usuario')?>">Usuarios</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
            <!-- END: Sidebar -->
        </div>
    </div>
</div>
<?= $this->endSection() ?>