<?php include_once __DIR__ . "/../partials/navbar.php"; ?>

<div class="message" id="redirect-home">
    <?php if (!isset($errorMessage)) { ?>
        <p class="success">
            Cuenta activada: <span class="email"><?php echo escapeHTML($email); ?></span></p>
    <?php } else { ?>
        <p class="error"> <span><?php echo escapeHTML($errorMessage); ?></span></p>
    <?php } ?>
</div>

<?php if (isset($errorMessage)) { ?>
    <a href="/" class="banner-button">Inicio</a>
<?php } else { ?><a href="/login" class="banner-button">Inciar sesión</a> <?php } ?>

<?php
$scripts = "
    <script src='/build/js/modules/navbar.min.js'></script>";
?>"