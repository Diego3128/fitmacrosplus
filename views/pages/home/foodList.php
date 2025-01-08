<?php require_once __DIR__ . "/../../partials/navbar.php"; ?>

<div class="title-container">
    <h2>Elige tus alimentos</h2>
</div>

<?php
$newFood = true;

require_once __DIR__ . "/../../partials/paginator.php"; ?>

<!-- create a functional componente for the food list -->
<div class="meal-summary stretched-container side-margin">
    <div class="meal-summary__group">
        <!-- list of food items -->
        <ul class="meal-summary__list">
            <?php foreach ($userFoods as $userFood): ?>
                <!-- food item -->
                <li class="meal-summary__item food-item borders">
                    <div class="flex-item flex-item--info">
                        <!-- name and calories -->
                        <div class="food-item__group">
                            <p class="food-item__name"><?php echo escapeHTML($userFood->name); ?></p>
                            <p class="food-item__kal"><?php echo escapeHTML($userFood->calories); ?> <span class="kcal">kcal</span></p>
                        </div>
                        <!-- size and macros -->
                        <div class="food-item__group">
                            <p class="food-item__size"><?php echo formatDecimal(escapeHTML($userFood->serving_size)); ?>
                                <?php foreach ($units as $unit):
                                    if ($unit->id === $userFood->serving_unit_id): ?>
                                        <span class="food-item__unit"><?php echo $unit->name; ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </p>
                            <p class="food-item__macros">
                                <span class="food-item__macro protein">p: <?php echo escapeHTML($userFood->protein); ?>g</span>
                                <span class="food-item__macro carbs">c: <?php echo escapeHTML($userFood->carbohydrate); ?>g</span>
                                <span class="food-item__macro fat">g: <?php echo escapeHTML($userFood->fat); ?>g</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex-item flex-item--options">
                        <button class="options-toggle">...</button>
                        <div class="options hidden">
                            <form
                                action="/home/new-record-detail/create?mealid=<?php echo $mealId ?? '' ?>&recordid=<?php echo $recordId ?? '' ?>&foodid=<?php echo $userFood->id ?>" method="post">
                                <input type="hidden" value="1" name="original_size">
                                <input class="option" type="submit" value="Agregar">
                            </form>
                            <form action="/home/new-record-detail/create" method="get">
                                <input type="hidden" value="<?php echo $mealId ?? '' ?>" name="mealid">
                                <input type="hidden" value="<?php echo $recordId ?? '' ?>" name="recordid">
                                <input type="hidden" value="<?php echo $userFood->id ?>" name="foodid">
                                <input class="option" type="submit" value="Editar Porción">
                            </form>
                        </div>
                    </div>

                </li>
            <?php endforeach; ?>

        </ul>

    </div>

</div>



<?php
require_once __DIR__ . "/../../partials/footer.php";
?>
<?php
//scripts used in the home page:
$scripts = "
<script src='/build/js/modules/foodItem.min.js'></script>
<script src='/build/js/modules/navbar.min.js'></script>";
?>