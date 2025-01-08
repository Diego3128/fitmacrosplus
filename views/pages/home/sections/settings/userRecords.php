<?php
require_once __DIR__ . "/../../../../partials/navbar.php";
require_once __DIR__ . "/../../../../partials/alerts.php";
?>

<div class="title-container">
    <h2>Consultar registros por mes y año</h2>
</div>
<?php
require_once __DIR__ . "/../../../../partials/paginator.php";
?>
<section class="settings-container stretched-container">
    <h2 class="text-center">Registros de usuario <?php echo $date ?></h2>

    <div class="date-seeker record-seeker ">
        <form class="form" action="" method="get">
            <label for="month-select">Mes:</label>
            <input type="month" id="month-select" name="month" value="<?php echo $month ?? date('m'); ?>">
            <label for="year-select">Año:</label>
            <input type="number" id="year-select" name="year" min="1900" max="2100" step="1" value="<?php echo $year ?? date('Y'); ?>">
            <button class="date-seeker__btn" type="submit">Buscar</button>
        </form>
    </div>


    <?php if (!empty($userRecords)): ?>
        <ul>
            <?php foreach ($userRecords as $record): ?>
                <li class="user-record flex-center">
                    <p class="date"><?php echo $record->date ?? ''; ?></p>
                    <div class="options flex">
                        <form action="/home" method="get">
                            <input type="hidden" name="date" value="<?php echo $record->date ?? date('Y-m-d'); ?>">
                            <button type="submit" class="update-button ">Cargar </button>
                        </form>
                        <form action="/home/delete-record" method="POST">
                            <input type="hidden" name="record" value="<?php echo $record->id ?? 0; ?>">
                            <button type="submit" class="update-button delete-button">Eliminar </button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>


</section>





<?php require_once __DIR__ . "/../../../../partials/footer.php"; ?>

<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>