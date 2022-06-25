<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idPlataforma" type="number" value="<?=$plataforma['CodigoPlataforma']?>">
    
    <div class="form-group row grupoNombrePlataforma">
        <label class="col-2"><strong>Plataforma</strong></label>
        <div class="col-10">
            <input class="nombrePlataforma form-control" value="<?=$plataforma['NombrePlataforma']?>" type="text">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Icono</strong></label>
        <div class="col-10">
            <select class="iconoPlataforma form-control">
                <option value="">Seleccionar plataforma</option>
                <option value="<?=base_url()?>/GoodGames/assets/images/icon-mouse.png" <?php if($plataforma['NombrePlataforma'] == 'PC') { ?>selected<?php } ?>>PC</option>
                <option value="<?=base_url()?>/GoodGames/assets/images/icon-gamepad.png" <?php if($plataforma['NombrePlataforma'] == 'PS4') { ?>selected<?php } ?>>PS4</option>
                <option value="<?=base_url()?>/GoodGames/assets/images/icon-ps5.png" <?php if($plataforma['NombrePlataforma'] == 'PS5') { ?>selected<?php } ?>>PS5</option>
                <option value="<?=base_url()?>/GoodGames/assets/images/icon-playstation.png" <?php if($plataforma['NombrePlataforma'] == 'PS5') { ?>selected<?php } ?>>PS4/PS5</option>
                <option value="<?=base_url()?>/GoodGames/assets/images/icon-gamepad-2.png" <?php if($plataforma['NombrePlataforma'] == 'XBOX') { ?>selected<?php } ?>>XBOX</option>
                <option value="<?=base_url()?>/GoodGames/assets/images/icon-tv.png" <?php if($plataforma['NombrePlataforma'] == 'TV') { ?>selected<?php } ?>>TV</option>
            </select>
            <img id="verImagen">
        </div>
    </div>

    <div class="mensajePlataforma"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('plataforma')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarPlataforma nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/plataforma.js"></script>
<?= $this->endSection() ?>