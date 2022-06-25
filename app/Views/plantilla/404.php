<?php
    use App\Models\ConfiguracionModel;
    use App\Models\RedesSocialesModel;

    $configuraciones = new ConfiguracionModel();
    $sociales = new RedesSocialesModel();

    $configuracion = $configuraciones->first();
    $socialess = $sociales->findAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$configuracion['NombrePagina']?> | 404</title>
    <link rel="icon" type="image/png" href="<?=base_url().$configuracion['IconoPagina']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7cOpen+Sans:400,700">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/flickity/dist/flickity.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/photoswipe/dist/photoswipe.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/photoswipe/dist/default-skin/default-skin.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/bootstrap-slider/dist/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/vendor/summernote/dist/summernote-bs4.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/css/goodgames.css">
    <link rel="stylesheet" href="<?=base_url()?>/GoodGames/assets/css/custom.css">

    <script defer src="<?=base_url()?>/GoodGames/assets/vendor/fontawesome-free/js/all.js"></script>
    <script defer src="<?=base_url()?>/GoodGames/assets/vendor/fontawesome-free/js/v4-shims.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/jquery/dist/jquery.min.js"></script>
</head>

<body>
    <div class="nk-main">
        <div class="nk-fullscreen-block">
            <div class="nk-fullscreen-block-top">
                <div class="text-center">
                    <div class="nk-gap-4"></div>
                    <a href="<?=base_url()?>"><img src="<?=base_url().$configuracion['LogoPagina']?>"></a>
                    <div class="nk-gap-2"></div>
                </div>
            </div>
            <div class="nk-fullscreen-block-middle">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                            <h1 class="text-main-1" style="font-size: 150px;">404</h1>

                            <div class="nk-gap"></div>
                            <h2 class="h4">¡Elegiste el camino equivocado!</h2>

                            <div>O tal página simplemente no existe...<br>¿Quieres volver a la página de inicio?
                            </div>
                            <div class="nk-gap-3"></div>

                            <a href="<?=base_url()?>" class="nk-btn nk-btn-rounded nk-btn-color-white">Regresar a la página principal</a>
                        </div>
                    </div>
                    <div class="nk-gap-3"></div>
                </div>
            </div>
            <div class="nk-fullscreen-block-bottom">
                <div class="nk-gap-2"></div>
                <ul class="nk-social-links-2 nk-social-links-center">
                    <?php foreach ($socialess as $social) { ?>
                        <li><a class="nk-social" href="<?=$social['EnlaceSocial']?>" target="_blank">
                            <span class="<?=$social['IconoSocial']?>"></span></a></li>
                    <?php } ?>
                </ul>
                <div class="nk-gap-2"></div>
            </div>
        </div>
    </div>

    <div class="nk-page-background-fixed"
        style="background-image: url('<?=base_url()?>/GoodGames/assets/images/bg-fixed-2.jpg');"></div>

    <script src="<?=base_url()?>/GoodGames/assets/vendor/object-fit-images/dist/ofi.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/gsap/src/minified/TweenMax.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/gsap/src/minified/plugins/ScrollToPlugin.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/sticky-kit/dist/sticky-kit.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/jarallax/dist/jarallax-video.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/flickity/dist/flickity.pkgd.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/photoswipe/dist/photoswipe.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/photoswipe/dist/photoswipe-ui-default.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/jquery-countdown/dist/jquery.countdown.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/moment/min/moment.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/hammerjs/hammer.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/soundmanager2/script/soundmanager2-nodebug-jsmin.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/bootstrap-slider/dist/bootstrap-slider.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/vendor/summernote/dist/summernote-bs4.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/plugins/nk-share/nk-share.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/js/goodgames.min.js"></script>
    <script src="<?=base_url()?>/GoodGames/assets/js/goodgames-init.js"></script>
</body>

</html>