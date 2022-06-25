<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idMetodoPago" type="number" value="<?=$metodoPago['CodigoMetodo']?>">

    <div class="input-group form-group">
        <p class="custom-file">
            <input id="imagenMetodoPago" type="file" class="custom-file-input">
            <label class="custom-file-label">Seleccionar método de pago</label>
        </p>
    </div>
    <img id="verImagen" <?php if ($metodoPago['ImagenMetodo']) { ?> src="<?=base_url().$metodoPago['ImagenMetodo']?>" <?php } ?> style="width: 100%;">

    <div class="mensajeMetodoPago"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('pago')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarMetodoPago nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/pago.js"></script>
<?= $this->endSection() ?>