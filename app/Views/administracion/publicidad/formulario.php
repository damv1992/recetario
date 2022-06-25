<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idPublicidad" type="number" value="<?=$publicidad['CodigoPublicidad']?>">
    
    <div class="form-group row">
        <label class="col-2"><strong>Titular</strong></label>
        <div class="col-10">
            <input class="titularPublicidad form-control" value="<?=$publicidad['Titular']?>" type="text">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Descripcion</strong></label>
        <div class="col-10">
            <textarea id="descripcionPublicidad" class="descripcionPublicidad form-control ckeditor"><?=$publicidad['Descripcion']?></textarea>
        </div>
    </div>

    <div class="input-group form-group">
        <p class="custom-file">
            <input id="imagenPublicidad" type="file" class="imagenPublicidad custom-file-input">
            <label class="custom-file-label">Seleccionar imagen</label>
        </p>
    </div>
    <img id="verImagen" <?php if ($publicidad['ImagenPublicidad']) { ?> src="<?=base_url().$publicidad['ImagenPublicidad']?>" <?php } ?> style="width: 100%;">

    <div class="form-group row">
        <label class="col-2"><strong>Enlace</strong></label>
        <div class="col-10">
            <input class="enlacePublicidad form-control" value="<?=$publicidad['EnlacePublicidad']?>" type="text">
        </div>
    </div>

    <?php
    if ($publicidad['FechaHoraInicio']) $fechaInicio = date('Y-m-d', strtotime($publicidad['FechaHoraInicio']));
    else $fechaInicio = date('Y-m-d');
    if ($publicidad['FechaHoraFin']) $fechaFin = date('Y-m-d', strtotime($publicidad['FechaHoraFin']));
    else $fechaFin = date('Y-m-d');
    ?>
    <div class="form-group row">
        <label class="col-2"><strong>Inicio</strong></label>
        <div class="col-10">
            <input class="fechaInicioPublicidad form-control" value="<?=$fechaInicio?>" type="date">
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-2"><strong>Fin</strong></label>
        <div class="col-10">
            <input class="fechaFinPublicidad form-control" value="<?=$fechaFin?>" type="date">
        </div>
    </div>

    <div class="mensajePublicidad"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('publicidad')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarPublicidad nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/publicidad.js"></script>
<?= $this->endSection() ?>