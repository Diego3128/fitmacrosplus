<?php require_once __DIR__ . "/../../partials/navbar.php";

require_once __DIR__ . "/../../partials/alerts.php";

?>
<?php require_once __DIR__ . "/partials/sectionTabs.php"; ?>

<!-- profile section -->
<section class="stretched-container container section--hide" data-section-id="1">
    <h3>profile</h3>
</section>
<!-- dashboard section -->
<section class="dashboard stretched-container container section--hide" data-section-id="2">

    <div class="card-container">

        <?php require_once __DIR__ . "/partials/cardProgress.php"; ?>

        <?php require_once __DIR__ . "/partials/macrosSummary.php"; ?>

        <section class="card card--todays-meals card--todays-meals--desktop">
            <h2 class="card__title">Resumen del día</h2>
            <ul class="card__list meal-list">
                <li class="meal-list__item">
                    <span class="meal-list__icon meal-list__icon--breakfast"></span>
                    <span class="meal-list__name">Desayuno</span>
                    <span class="meal-list__calories">450 kcal</span>
                </li>
                <li class="meal-list__item">
                    <span class="meal-list__icon meal-list__icon--lunch"></span>
                    <span class="meal-list__name">Almuerzo</span>
                    <span class="meal-list__calories">650 kcal</span>
                </li>
                <li class="meal-list__item">
                    <span class="meal-list__icon meal-list__icon--dinner"></span>
                    <span class="meal-list__name">Comida</span>
                    <span class="meal-list__calories">550 kcal</span>
                </li>
            </ul>
        </section>

    </div>

    <?php require __DIR__ . "/partials/mealStats.php"; ?>

    <section class="card card--meal-summary">
        <h2 class="card__title">Tus comidas de hoy</h2>

        <?php require_once __DIR__ . "/partials/mealSummary.php"; ?>

    </section>

</section>

<!-- settings section -->
<section class="stretched-container container section--hide" data-section-id="3">
    <h3>settings</h3>
</section>



<?php
require_once __DIR__ . "/../../partials/footer.php";
?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/tabs.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>