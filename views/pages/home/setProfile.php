<?php require_once __DIR__ . "/../../partials/navbar.php"; ?>

<?php require_once __DIR__ . "/../../partials/alerts.php"; ?>



<div class="form-container stretched-container">
    <div class="form-header">
        <h2>Completa tu perfil antes de continuar</h2>
    </div>

    <form class="form-body" action="" method="POST">

        <?php require_once __DIR__ . "/forms/userProfileForm.php"; ?>

        <!-- Submit -->
        <div class="form-footer">
            <button class="btn btn-submit" type="submit"><span class="text">Guardar</span></button>
        </div>

    </form>


</div>


<?php require_once __DIR__ . "/../../partials/footer.php"; ?>

<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>