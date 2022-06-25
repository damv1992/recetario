<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idRedSocial" type="number" value="<?=$redSocial['CodigoSocial']?>" style="display:none;">
    <a href="https://fontawesome.com/v4/icons/" target="_blank">Iconos</a>

    <div class="form-group row">
        <label class="col-2"><strong>Enlace</strong></label>
        <div class="col-10">
            <input class="enlaceRedSocial form-control" placeholder="Enlace de la red social" value="<?=$redSocial['EnlaceSocial']?>" type="text">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Icono</strong></label>
        <div class="col-10">
            <input class="iconoRedSocial form-control" placeholder="Icono de la red social" value="<?=$redSocial['IconoSocial']?>" type="text">
        </div>
    </div>
    
    <div class="mensajeRedSocial"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('social')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarRedSocial nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/social.js"></script>
<?= $this->endSection() ?>