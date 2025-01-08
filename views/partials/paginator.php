<div class="paginator flex container">
    <a class="paginator__btn btn" href="<?php echo $previousUrl ?? '/home'; ?>">Volver</a>
    <?php if (isset($newFood)) { ?>
        <a class="paginator__btn btn" href="/home/newfood">Crear nuevo</a>
    <?php } ?>
</div>