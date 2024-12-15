<div class="message">
    <?php if (!isset($errorMessage)) { ?>
        <p class="success">
            Token de confirmación enviado a: <span class="email"><?php echo escapeHTML($email); ?></span></p>
    <?php } else { ?>
        <p class="error">Error: <span><?php echo escapeHTML($errorMessage); ?></span></p>
    <?php } ?>
</div>

<a href="/" class="banner-button">Inicio</a>