<?php require_once __DIR__ . "/../../../../partials/navbar.php";

require_once __DIR__ . "/../../../../partials/alerts.php"; ?>

<section class="food-creation form-container">
    <div class="form-header">
        <h2 class="food-creation__title">Crear nueva comida</h2>
    </div>

    <?php require_once __DIR__ . "/../../../../partials/paginator.php"; ?>

    <form action="" method="POST" class="food-creation__form form-body">

        <?php require_once __DIR__ . "/forms/mealform.php"; ?>

        <div class="form-footer">
            <button type="submit" class="btn btn-submit"><span class="text">Guardar</span></button>
        </div>

    </form>
</section>


<?php
require_once __DIR__ . "/../../../../partials/footer.php";

?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>