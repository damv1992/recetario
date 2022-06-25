<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idTipo" type="number" value="<?=$tipo['CodigoTipo']?>" style="display:none;">

    <div class="form-group row">
        <label class="col-3"><strong>Tipo de producto</strong></label>
        <div class="col-9">
            <input class="nombreTipo form-control" value="<?=$tipo['NombreTipo']?>" type="text">
        </div>
    </div>
    
    <div class="mensajeTipo"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('tipo')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarTipo nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/tipo.js"></script>
<?= $this->endSection() ?>