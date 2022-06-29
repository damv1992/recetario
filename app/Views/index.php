<?= $this->extend('plantilla') ?>

<?= $this->section('estilos') ?>
    <title><?=$configuracion['NombrePagina'].' | '.$titulo?></title>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
    <!-- Recipes Categories -->
    <section id="categories">
        <div class="container botonesFiltroRecetas"></div>
    </section>

    <!-- Recipes Items -->
    <section id="items">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Recetas</h2>
                </div>
            </div>
            <div class="row resultadosFiltroRecetas"></div>
            <div class="paginasFiltroRecetas"></div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url() ?>/RecipeBook/js/custom/filtro.js"></script>
<?= $this->endSection() ?>