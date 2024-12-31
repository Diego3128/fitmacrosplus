<?php require_once __DIR__ . "/../../partials/navbar.php";

require_once __DIR__ . "/../../partials/alerts.php"; ?>

<section class="food-creation form-container">
    <div class="form-header">
        <h2 class="food-creation__title">Información nutricional</h2>
    </div>

    <form action="" method="POST" class="food-creation__form form-body" novalidate>

        <?php require_once __DIR__ . "/forms/foodForm.php"; ?>

        <div class="form-footer">
            <button type="submit" class="btn btn-submit"><span class="text">Actualizar</span></button>

            <a href="<?php $_SERVER['HTTP_REFERER'] ?? '/home'; ?>" class="food-creation__cancel">Cancelar</a>
        </div>

    </form>
</section>

<?php
require_once __DIR__ . "/../../partials/footer.php";
?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/foodForm.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>