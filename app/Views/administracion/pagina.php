<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>

    <div class="form-group row">
        <strong class="col-lg-3 col-md-12 text-white">Nombre de la página</strong>
        <div class="col-lg-9 col-md-12">
            <input class="nombrePagina form-control" value="<?=$configuracion['NombrePagina']?>" type="text">
        </div>
    </div>

    <div class="form-group row">
        <strong class="col-lg-3 col-md-12 text-white">Frase</strong>
        <div class="col-lg-9 col-md-12">
            <input class="frasePagina form-control" value="<?=$configuracion['FrasePagina']?>" type="text">
        </div>
    </div>

    <div class="input-group form-group">
        <p class="custom-file">
            <input class="iconoPagina custom-file-input" type="file">
            <label class="custom-file-label">Icono</label>
        </p>
            <img id="verIcono" <?php if ($configuracion['IconoPagina']) { ?> src="<?=base_url().$configuracion['IconoPagina']?>" <?php } ?> height="35">
    </div>

    <div class="input-group form-group">
        <p class="custom-file">
            <input class="logoPagina custom-file-input" type="file">
            <label class="custom-file-label">Logo</label>
        </p>
        <div class="input-group-prepend">
            <img id="verLogo" <?php if ($configuracion['LogoPagina']) { ?> src="<?=base_url().$configuracion['LogoPagina']?>" <?php } ?> height="35">
        </div>
    </div>

    <div class="form-group row">
        <strong class="col-lg-3 col-md-12 text-white">Sobre Nosotros</strong>
        <div class="col-lg-9 col-md-12">
            <textarea id="sobrePagina" class="sobrePagina form-control"><?=$configuracion['SobreNosotros']?></textarea>
        </div>
    </div>

    <!--<div class="form-group row">
        <strong class="col-lg-3 col-md-12 text-white">Estado de la página</strong>
        <div class="col-lg-9 col-md-12">
            <input type="checkbox" checked data-toggle="toggle" data-onstyle="info">
        </div>
    </div>-->

    <div class="mensajeConfiguracion form-group"></div>
    <div class="form-group">
        <a href="<?=site_url('administracion')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
        <button class="btnGuardarConfiguracion nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fas fa-save"></i> Guardar</button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/pagina.js"></script>
<?= $this->endSection() ?>