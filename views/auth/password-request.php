    <!-- navbar -->
    <?php include_once __DIR__ . "/../partials/navbar.php"; ?>

    <!-- Notifications -->

    <?php include_once __DIR__ . "/../partials/alerts.php"; ?>



    <div class="form-container" id="redirect-home">

        <div class="form-header">
            <h2>Restablece tu contraseña en Fitmacros+</h2>
        </div>

        <form action="/password/request" method="post" class="form-body">

            <label for="email">Correo Electrónico</label>
            <input
                type="email"
                id="email"
                name="user[email]"
                value="<?php echo escapeHTML($user->email); ?>"
                placeholder="Ingresa tu correo electrónico"
                required
                maxlength="80">

            <div class="form-footer">
                <button type="submit" class="btn btn-submit"><span class="text">Restablecer</span></button>
            </div>

            <div class="form-options">
                <a href="/">Inicio</a>
            </div>
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