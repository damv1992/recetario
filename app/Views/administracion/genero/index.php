<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <a href="<?=site_url('genero/nuevo')?>" class="nk-btn nk-btn-rounded nk-btn-color-primary form-group">Agregar</a>
    <table class="tablaGeneros nk-table">
        <thead>
            <tr>
                <th style="width: 1%;">#</th>
                <th>Género</th>
                <th style="width: 1%;">Acciones</th>
            </tr>
        </thead>
    </table>
    <a href="<?=site_url('administracion')?>" class="nk-btn nk-btn-rounded nk-btn-outline nk-btn-color-info form-group">
        <i class="fas fa-angle-double-left"></i> Volver</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/genero.js"></script>
<?= $this->endSection() ?>