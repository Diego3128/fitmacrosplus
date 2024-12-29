<?php require_once __DIR__ . "/../../partials/navbar.php";

require_once __DIR__ . "/../../partials/alerts.php";

?>
<?php require_once __DIR__ . "/partials/sectionTabs.php"; ?>


<!-- dashboard section -->
<section class="dashboard stretched-container container" data-section-id="2">

    <div class="card-container">

        <?php require_once __DIR__ . "/partials/cardProgress.php"; ?>

        <?php require_once __DIR__ . "/partials/macrosSummary.php"; ?>

        <section class="card card--todays-meals card--todays-meals--desktop">
            <h2 class="card__title">Resumen del día</h2>
            <ul class="card__list meal-list">
                <?php if (isset($userMeals) and isset($mealStats)): ?>
                    <?php foreach ($userMeals as $userMeal): ?>
                        <li class="meal-list__item">
                            <span class="meal-list__icon meal-list__icon--breakfast"></span>
                            <span class="meal-list__name"><?php echo escapeHTML($userMeal->name); ?></span>
                            <span class="meal-list__calories"><?php echo $mealStats[$userMeal->name]["calories"] ?? '0' ?> kcal</span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </section>

    </div>

    <?php require __DIR__ . "/partials/mealStats.php"; ?>

    <section class="card card--meal-summary">
        <h2 class="card__title">Registro || Fecha:<?php if (isset($date)) echo $date ?></h2>

        <?php if (isset($emptyRecord)) require_once __DIR__ . "/partials/emptySummaryAlert.php";  ?>

        <?php require_once __DIR__ . "/partials/mealSummary.php"; ?>

    </section>

</section>


<?php
require_once __DIR__ . "/../../partials/footer.php";
?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/navbar.min.js'></script>
<script src='/build/js/modules/foodItem.min.js'></script>
<script src='/build/js/modules/tabs.min.js'></script>
<script src='/build/js/modules/notifications.min.js'></script>";
?>