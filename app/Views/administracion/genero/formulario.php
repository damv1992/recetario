<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idGenero" type="number" value="<?=$genero['CodigoGenero']?>" style="display:none;">

    <div class="form-group row">
        <label class="col-2"><strong>Genero</strong></label>
        <div class="col-10">
            <input class="nombreGenero form-control" value="<?=$genero['NombreGenero']?>" type="text">
        </div>
    </div>
    
    <div class="mensajeGenero"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('genero')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarGenero nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/genero.js"></script>
<?= $this->endSection() ?>