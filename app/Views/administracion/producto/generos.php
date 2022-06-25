<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idProducto" type="number" value="<?=$producto['CodigoProducto']?>">
    <div class="form-group">
        <div class="nk-box-2 bg-dark-2">
            <h4><?=$producto['NombreProducto'].' - '.$plataforma['NombrePlataforma']?></h4>
            <center><img src="<?=$producto['FotoProducto']?>" width="300"></center>
        </div>
    </div>

    <a href="<?=site_url('producto/asignar/'.$producto['CodigoProducto'])?>" class="nk-btn nk-btn-rounded nk-btn-color-primary form-group">Agregar</a>
    <table class="tablaGenerosProducto nk-table">
        <thead>
            <tr>
                <th style="width: 1%;">#</th>
                <th>Genero</th>
                <th style="width: 1%;">Acciones</th>
            </tr>
        </thead>
    </table>
    <a href="<?=site_url('producto')?>" class="nk-btn nk-btn-rounded nk-btn-outline nk-btn-color-info form-group">
        <i class="fas fa-angle-double-left"></i> Volver</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/producto.js"></script>
<?= $this->endSection() ?>