<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
    <meta name="keywords" content="game, gaming, ps4, bolivia, shop, tienda, streaming">
    <link rel="icon" type="image/png" href="<?=base_url().$configuracion['IconoPagina']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7cOpen+Sans:400,700">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/flickity/dist/flickity.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/photoswipe/dist/photoswipe.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/photoswipe/dist/default-skin/default-skin.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/bootstrap-slider/dist/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/vendor/summernote/dist/summernote-bs4.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/css/goodgames.css">
    <link rel="stylesheet" href="<?= base_url() ?>/GoodGames/assets/css/custom.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <?= $this->renderSection('estilos') ?>

    <script defer src="<?= base_url() ?>/GoodGames/assets/vendor/fontawesome-free/js/all.js"></script>
    <script defer src="<?= base_url() ?>/GoodGames/assets/vendor/fontawesome-free/js/v4-shims.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/basic/ckeditor.js"></script>
    <!-- DataTables & Plugins -->
    <script src="https://adminlte.io/themes/v3/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="https://adminlte.io/themes/v3/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
</head>

<body>
    <input class="site_url" value="<?=site_url()?>" type="text" hidden>
    <header class="nk-header nk-header-opaque">
        <!-- START: Top Contacts -->
        <div class="nk-contacts-top">
            <div class="container">
                <div class="nk-contacts-left">
                    <ul class="nk-social-links">
                        <?php foreach($sociales as $social) { ?>
                            <li><a href="<?=$social['EnlaceSocial']?>" target="_blank"><span class="<?=$social['IconoSocial']?>"></span></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="nk-contacts-right">
                    <ul class="nk-contacts-icons">
                        <!--<li>
                            <a href="#" data-toggle="modal" data-target="#modalSearch">
                                <span class="fa fa-search"></span>
                            </a>
                        </li>-->
                        <li>
                            <?php if ($_SESSION['Usuario'] && $_SESSION['RolAsignado'] == 'Administrador') { ?>
                                <span class="nk-cart-toggle"><?=$_SESSION['Usuario']?></span>
                                <div class="nk-cart-dropdown col-md-7">
                                    <a href="<?=site_url('administracion')?>"><span class="fa fa-folder"></span> Administración</a><br>
                                    <a href="<?=site_url('usuario/desconectar')?>"><span class="fa fa-sign-out"></span> Desconectar</a>
                                </div>
                            <?php } else { ?>
                                <a href="#" data-toggle="modal" data-target="#modalLogin">
                                    <span class="fa fa-user"></span>
                                </a>
                            <?php } ?>
                        </li>

                        <?php
                        use App\Models\ProductosModel;
                        use App\Models\PlataformaModel;
                        $productos = new ProductosModel();
                        $plataformas = new PlataformaModel();
                        $cantidad = count($carritos);
                        $total = 0;
                        if ($cantidad > 0) {
                            if ($cantidad > 1) $pedir = 'Pedir productos (Bs. '.$total.')';
                            else $pedir = 'Pedir producto';
                        }
                        ?>
                        <li id="carrito">
                            <span class="nk-cart-toggle">
                                <span class="fa fa-shopping-cart"></span>
                                <span class="nk-badge"><?=$cantidad?></span>
                            </span>
                            <div class="nk-cart-dropdown">

                                <?php
                                foreach ($carritos as $carrito) {
                                    $producto = $productos->where('CodigoProducto', $carrito['Producto'])->first();
                                    $plataforma = $plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
                                    $total += $producto['Precio'];
                                    $detalle = site_url('producto/detalle/'.$producto['CodigoProducto']);
                                ?>
                                    <div class="nk-widget-post">
                                        <a href="<?=$detalle?>" class="nk-post-image">
                                            <img src="<?=$producto['FotoProducto']?>">
                                        </a>
                                        <h3 class="nk-post-title">
                                            <a href="#" onclick="retirarCarrito(<?=$producto['CodigoProducto']?>);" class="nk-cart-remove-item">
                                                <span class="ion-android-close"></span>
                                            </a>
                                            <a href="<?=$detalle?>"><?=$producto['NombreProducto'].' ('.$plataforma['NombrePlataforma'].')'?></a>
                                        </h3>
                                        <div class="nk-product-price"><?='Bs. '.$producto['Precio']?></div>
                                    </div>
                                <?php } ?>

                                <div class="nk-gap-2"></div>
                                <?php if ($cantidad > 0) { ?>
                                    <div class="text-center">
                                        <a href="<?=site_url('catalogo/carrito')?>"
                                            class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white"><?=$pedir?></a>
                                    </div>
                                <?php } else { ?>
                                    <div class="text-center color-white">Tu carrito está vacío</div>
                                <?php } ?>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Top Contacts -->

        <!-- Menu -->
        <nav class="nk-navbar nk-navbar-top nk-navbar-sticky nk-navbar-autohide">
            <div class="container">
                <div class="nk-nav-table">
                    <a href="<?=base_url()?>" class="nk-nav-logo"><img src="<?=base_url().$configuracion['LogoPagina']?>"></a>

                    <ul class="nk-nav nk-nav-right d-none d-lg-table-cell" data-nav-mobile="#nk-nav-mobile">
                        <li class=" nk-drop-item">
                            <a href="#">Tienda</a>
                            <ul class="dropdown">
                                <li><a href="<?=site_url('catalogo')?>">Catálogo</a></li>
                                <li><a href="<?=site_url('home/alquiler')?>">Alquiler de cuenta</a></li>
                                </li>
                            </ul>
                        </li>
                        <li class=" nk-drop-item">
                            <a href="elements.html">Features</a>
                            <ul class="dropdown">
                                <li><a href="elements.html">Elements (Shortcodes)</a></li>
                                <li class=" nk-drop-item"><a href="forum.html">Forum</a>
                                    <ul class="dropdown">
                                        <li><a href="forum.html">Forum</a></li>
                                        <li><a href="forum-topics.html">Topics</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="nk-nav nk-nav-right nk-nav-icons">
                        <li class="single-icon d-lg-none">
                            <a href="#" class="no-link-effect" data-nav-toggle="#nk-nav-mobile">
                                <span class="nk-icon-burger">
                                    <span class="nk-t-1"></span>
                                    <span class="nk-t-2"></span>
                                    <span class="nk-t-3"></span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div id="nk-nav-mobile" class="nk-navbar nk-navbar-side nk-navbar-right-side nk-navbar-overlay-content d-lg-none">
        <div class="nano">
            <div class="nano-content">
                <a href="<?=base_url()?>" class="nk-nav-logo">
                    <img src="<?=base_url().$configuracion['LogoPagina']?>" width="120">
                </a>
                <div class="nk-navbar-mobile-content"><ul class="nk-nav"></ul></div>
            </div>
        </div>
    </div>

    <div class="nk-main">
        <div class="nk-gap-2"></div>
        <div class="container">
            <?= $this->renderSection('contenido') ?>
        </div>
        

        <div class="nk-gap-4"></div>
        <!-- START: Footer -->
        <footer class="nk-footer">

            <div class="container">
                <div class="nk-gap-3"></div>
                <div class="row vertical-gap">
                    <div class="col-md-6">
                        <div class="nk-box-2 bg-dark-2">
                            <h4>Sobre Nosotros</h4>
                            <div class="text-justify"><?=$configuracion['SobreNosotros']?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="nk-box-2 bg-dark-2">
                            <h4>Métodos de pago</h4>
                            <?php foreach ($pagos as $pago) { ?>
                                <img src="<?=base_url().$pago['ImagenMetodo']?>">
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="nk-gap-3"></div>
            </div>

            <div class="nk-copyright">
                <div class="container">
                    <div class="nk-copyright-left">
                        <a href="<?=base_url()?>"><?=$configuracion['NombrePagina']?></a>
                    </div>
                    <div class="nk-copyright-right">
                        <ul class="nk-social-links-2">
                            <!-- Red Social -->
                            <?php foreach($sociales as $social) { ?>
                                <li><a href="<?=$social['EnlaceSocial']?>" target="_blank"><span class="<?=$social['IconoSocial']?>"></span></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END: Footer -->
    </div>

    <!-- START: Page Background -->
    <img class="nk-page-background-top" src="<?= base_url() ?>/GoodGames/assets/images/bg-top.png" alt="">
    <img class="nk-page-background-bottom" src="<?= base_url() ?>/GoodGames/assets/images/bg-bottom.png" alt="">
    <!-- END: Page Background -->

    <!-- START: Search Modal -->
    <div class="nk-modal modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ion-android-close"></span>
                    </button>

                    <h4 class="mb-0">Buscar</h4>

                    <div class="nk-gap-1"></div>
                    <form action="#" class="nk-form nk-form-style-1">
                        <input type="text" value="" name="search" class="form-control"
                            placeholder="Type something and press Enter" autofocus>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Search Modal -->

    <!-- START: Login Modal -->
    <div class="nk-modal modal fade" id="modalLogin" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ion-android-close"></span>
                    </button>

                    <h4 class="mb-0"><span class="text-main-1">Acceso</span> al Sistema</h4>

                    <div class="nk-gap-1"></div>
                    <div class="row vertical-gap">
                        <div class="col-md-12">
                            <div class="nk-gap"></div>
                            <input class="userLogin required form-control" type="text" placeholder="Nombre de usuario">

                            <div class="nk-gap"></div>
                            <input class="contrasenaLogin required form-control" type="password" placeholder="Contraseña">
                        </div>
                    </div>

                    <div class="nk-gap-1"></div>
                    <div class="row vertical-gap">
                        <div class="col-md-6">
                            <button class="btnLogin nk-btn nk-btn-rounded nk-btn-color-white nk-btn-block">Iniciar Sesión</button>
                        </div>
                        <div class="col-md-6">
                            <div class="mnt-5">
                                <small><a href="#">Not a member? Sign up</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Login Modal -->

    <script src="<?= base_url() ?>/GoodGames/assets/vendor/object-fit-images/dist/ofi.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/gsap/src/minified/TweenMax.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/gsap/src/minified/plugins/ScrollToPlugin.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/sticky-kit/dist/sticky-kit.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/jarallax/dist/jarallax-video.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/flickity/dist/flickity.pkgd.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/photoswipe/dist/photoswipe.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/photoswipe/dist/photoswipe-ui-default.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/moment/min/moment.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/hammerjs/hammer.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/bootstrap-slider/dist/bootstrap-slider.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/vendor/summernote/dist/summernote-bs4.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/plugins/nk-share/nk-share.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/js/goodgames.min.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/js/goodgames-init.js"></script>

    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/usuario.js"></script>
    <script src="<?= base_url() ?>/GoodGames/assets/js/custom/carrito.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>