<?php
require_once __DIR__ . "/../../../../partials/navbar.php";
require_once __DIR__ . "/../../partials/sectionTabs.php";
?>

<div class="settings-container stretched-container">

    <div class="settings-group">
        <h2>Contenido</h2>
        <div class="setting-item">
            <p class="info">Historial de registros</p>
            <a href="/home/records" class="update-button">Registros</a>
        </div>
        <div class="setting-item">
            <p class="info">Alimentos</p>
            <a href="/home/foods" class="update-button">Alimentos</a>
        </div>
    </div>

    <div class="settings-group">
        <h2>Tiempos de comida</h2>
        <?php foreach ($mealList as $meal): ?>
            <div class="setting-item">
                <p class="info "><?php echo $meal->name ?? ''; ?></p>
                <div class="options">
                    <?php if ($meal->deletable === '1'): ?>
                        <form action="/home/delete-meal" method="post">
                            <input type="hidden" name="meal" value="<?php echo $meal->id ?? 0 ?>">
                            <button type="submit" class="update-button delete-button">Eliminar</button>
                        </form>
                    <?php endif; ?>
                    <a href="/home/edit-meal?meal=<?php echo $meal->id ?? 0 ?>" class="update-button">Editar</a>
                </div>
            </div>
        <?php endforeach; ?>
        <a href="/home/create-meal" class="update-button">Nuevo</a>

    </div>

    <div class="settings-group">
        <h2>Cuenta</h2>
        <div class="setting-item">
            <p class="info">Email</p>
            <p class="info email"><?php echo $basicSettings->user_email ?? ''; ?></p>
        </div>
        <div class="setting-item">
            <p class="info">Nombre</p>
            <p class="info"><?php echo $basicSettings->user_name . " " . $basicSettings->user_lastname  ?? ''; ?></p>
        </div>

        <button id="dropAccount-btn" class="update-button delete-button">Eliminar cuenta</button>
    </div>

    <div class="settings-group">
        <h2>Formula: </h2>
        <div class="setting-item">
            <p class="info"><?php echo $basicSettings->formula_name ?? ''; ?></p>
            <p class="info "><?php echo $basicSettings->formula_expression ?? ''; ?></p>
        </div>
    </div>

</div>


<?php require_once __DIR__ . "/../../../../partials/footer.php"; ?>

<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/tabs.min.js'></script>
<script src='/build/js/pages/settings.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>