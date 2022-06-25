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

    <div class="form-group row">
        <label class="col-2"><strong>Genero</strong></label>
        <div class="col-10">
            <select class="generoProducto form-control">
                <option value="">Seleccionar género</option>
                <?php foreach ($generos as $genero) { ?>
                    <option value="<?=$genero['CodigoGenero']?>"
                        <?php if($genero['CodigoGenero'] == $generosProducto['Genero']) { ?>selected<?php } ?>>
                        <?=$genero['NombreGenero']?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="mensajeGeneroProducto"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('alquiler/generos/'.$producto['CodigoProducto'])?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4">
                <i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarGeneroProducto nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/alquiler.js"></script>
<?= $this->endSection() ?>