<?php
    use App\Models\ProductosModel;
    use App\Models\PlataformaModel;

    $productos = new ProductosModel();
    $plataformas = new PlataformaModel();
    $total = 0;
?>
<?= $this->extend('plantilla/index') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
    <!-- START: Order Products -->
    <div class="nk-gap-3"></div>
    <h3 class="nk-decorated-h-2"><span><span class="text-main-1">Su</span> Pedido</span></h3>
    <div class="nk-gap"></div>
    <div class="table-responsive">
        <table class="nk-table nk-table-sm">
            <thead class="thead-default">
                <tr>
                    <th class="nk-product-cart-title">Producto</th>
                    <th class="nk-product-cart-total">Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($carritos as $carrito) {
                    $producto = $productos->where('CodigoProducto', $carrito['Producto'])->first();
                    $plataforma = $plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
                    $total += $producto['Precio'];
                    $imagen = '<img src="'.$producto['FotoProducto'].'" height="50">'
                ?>
                    <tr>
                        <td class="nk-product-cart-title">
                            <?=$imagen.' '.$producto['NombreProducto']?> (<?=$plataforma['NombrePlataforma']?>)
                        </td>
                        <td class="nk-product-cart-total">
                            Bs. <?=$producto['Precio']?>
                        </td>
                    </tr>
                <?php } ?>

                <tr class="nk-store-cart-totals-total">
                    <td>Total</td>
                    <td>Bs. <?=$total?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- END: Order Products -->

    <div class="nk-gap-2"></div>
    <a class="nk-btn nk-btn-rounded nk-btn-color-main-1" href="<?=site_url('catalogo/pedir')?>">Realizar Pedido</a>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!--<script src="<?=base_url()?>/GoodGames/assets/js/custom/filtro.js"></script>-->
<?= $this->endSection() ?>