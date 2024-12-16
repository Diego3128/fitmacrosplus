    <!-- navbar -->
    <?php include_once __DIR__ . "/../partials/navbar.php"; ?>

    <!-- Notifications -->

    <?php include_once __DIR__ . "/../partials/alerts.php"; ?>


    <div class="form-container">

        <div class="form-header">
            <h2>Inicia Sesión</h2>
        </div>

        <form method="post" class="form-body">
            <?php include_once __DIR__ . "/forms/loginForm.php"; ?>
        </form>

    </div>

    <!-- footer -->
    <?php include_once __DIR__ . "/../partials/footer.php"; ?>


    <?php
    //scripts used in the home page:
    $scripts = "
    <script src='/build/js/modules/navbar.min.js'></script>
    <script src='/build/js/modules/notifications.min.js'></script>";
    ?>