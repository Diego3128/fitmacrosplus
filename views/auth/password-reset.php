    <!-- navbar -->
    <?php include_once __DIR__ . "/../partials/navbar.php"; ?>

    <!-- Notifications -->

    <?php include_once __DIR__ . "/../partials/alerts.php"; ?>


    <?php if (!$tokenError && !$redirect): ?>
        <div class="form-container">

            <div class=" form-header">
                <h2>Crea una nueva contraseña</h2>
            </div>

            <form action="" method="post" class="form-body">

                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="user[password]"
                    placeholder="Nueva contraseña"
                    required
                    maxlength="50"
                    minlength="6">

                <div class="form-footer">
                    <button type="submit" class="btn btn-submit"><span class="text">Restablecer</span></button>
                </div>

                <div class="form-options">
                    <a href="/">Inicio</a>
                </div>
            </form>

        </div>

    <?php endif; ?>

    <?php if ($redirect || $tokenError): ?>
        <div class="empty-container" id="redirect-home"> </div>
    <?php endif; ?>

    <!-- footer -->
    <?php include_once __DIR__ . "/../partials/footer.php"; ?>


    <?php
    //scripts used in the home page:
    $scripts = "
    <script src='/build/js/modules/navbar.min.js'></script>
    <script src='/build/js/modules/notifications.min.js'></script>";
    ?>