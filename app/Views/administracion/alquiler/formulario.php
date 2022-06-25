<?= $this->extend('plantilla/administracion') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenidoMenu') ?>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1"><?=$titulo?></span> </span></h3>
    <input id="idProducto" type="number" value="<?=$producto['CodigoProducto']?>">
    
    <div class="form-group row">
        <label class="col-2"><strong>Producto</strong></label>
        <div class="col-10">
            <input class="nombreProducto form-control" value="<?=$producto['NombreProducto']?>" type="text">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Descripcion</strong></label>
        <div class="col-10">
            <textarea id="descripcionProducto" class="descripcionProducto form-control ckeditor"><?=$producto['Descripcion']?></textarea>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Imagen</strong></label>
        <div class="col-10">
            <input class="imagenProducto form-control" value="<?=$producto['FotoProducto']?>" type="text">
            <img class="verImagen" <?php if ($producto['FotoProducto']) { ?> src="<?=$producto['FotoProducto']?>" <?php } ?> height="300">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Precio</strong></label>
        <div class="col-9">
            <input class="precioProducto form-control" value="<?=$producto['Precio']?>" type="number">
        </div>
        <label class="col-1">Bs.</label>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Plataforma</strong></label>
        <div class="col-10">
            <select class="plataformaProducto form-control">
                <option value="">Seleccionar plataforma</option>
                <?php foreach ($plataformas as $plataforma) { ?>
                    <option value="<?=$plataforma['CodigoPlataforma']?>"
                        <?php if($plataforma['CodigoPlataforma'] == $producto['Plataforma']) { ?>selected<?php } ?>>
                        <?=$plataforma['NombrePlataforma']?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-2"><strong>Tipo</strong></label>
        <div class="col-10">
            <select class="tipoProducto form-control">
                <option value="">Seleccionar tipo de producto</option>
                <?php foreach ($tipos as $tipo) { ?>
                    <option value="<?=$tipo['CodigoTipo']?>"
                        <?php if($tipo['CodigoTipo'] == $producto['Tipo']) { ?>selected<?php } ?>>
                        <?=$tipo['NombreTipo']?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="mensajeProducto"></div>
    <div class="form-group row">
        <div class="col-12">
            <a href="<?=site_url('alquiler')?>" class="nk-btn nk-btn-rounded nk-btn-color-main-4"><i class="fa fa-arrow-left"></i> Volver</a>
            <button class="btnGuardarProducto nk-btn nk-btn-rounded nk-btn-color-main-3 float-right"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/alquiler.js"></script>
<?= $this->endSection() ?>