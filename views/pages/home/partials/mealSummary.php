<div class="meal-summary">
    <?php
    // print all the record details
    if (!isset($emptyRecord) && isset($userMeals) and isset($processedMealDetails)) {
        foreach ($userMeals as $userMeal): ?>
            <div class="meal-summary__group">
                <!-- header -->
                <div class="meal-summary__header">
                    <h4 class="meal-summary__meal-title"><?php echo escapeHTML($userMeal->name) ?></h4>
                    <form action="/home/new-record-detail" method="get">
                        <input type="hidden" value="<?php echo $userMeal->id  ?>" name="mealid">
                        <input type="hidden" value="<?php echo $userRecordId ?? '' ?>" name="recordid">
                        <button type="submit" class="meal-summary__btn add-btn flex">Nuevo</button>
                    </form>


                </div>
                <!-- list of food items -->
                <ul class="meal-summary__list">
                    <?php foreach ($processedMealDetails as $processedMealDetail) {
                        // $processedMealDetails can be empty sometimes (when starting a new record)
                        if ($processedMealDetail->meal_id === $userMeal->id) { ?>
                            <!-- food item -->
                            <li class="meal-summary__item food-item">
                                <div class="flex-item flex-item--info">
                                    <!-- name and calories -->
                                    <div class="food-item__group">
                                        <p class="food-item__name"><?php echo escapeHTML($processedMealDetail->food) ?></p>
                                        <p class="food-item__kal"><?php echo escapeHTML($processedMealDetail->calories) ?>
                                            <span class="kcal">kcal</span>
                                        </p>
                                    </div>
                                    <!-- size and macros -->
                                    <div class="food-item__group">
                                        <p class="food-item__size">
                                            <?php echo escapeHTML($processedMealDetail->consumed_quantity) ?>
                                            <span class="food-item__unit">
                                                <?php echo escapeHTML($processedMealDetail->unit); ?>
                                            </span>
                                        </p>
                                        <p class="food-item__macros">
                                            <span class="food-item__macro protein">p:
                                                <?php echo escapeHTML($processedMealDetail->protein) ?>g
                                            </span>
                                            <span class="food-item__macro carbs">c:
                                                <?php echo escapeHTML($processedMealDetail->carbs) ?>g
                                            </span>
                                            <span class="food-item__macro fat">f:
                                                <?php echo escapeHTML($processedMealDetail->fat) ?>g
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex-item flex-item--options">
                                    <button class="options-toggle">...</button>
                                    <div class="options hidden">
                                        <?php
                                        $params = generateRecordParams(
                                            $processedMealDetail->record_detail_id,
                                            $processedMealDetail->meal_id
                                        );
                                        ?>
                                        <a class="option" href="/home/editRecord?<?php echo $params ?>">Editar</a>
                                        <form action="/home/deleteRecord" method="post">
                                            <input type="hidden"
                                                value="<?php echo $processedMealDetail->record_detail_id ?>"
                                                name="record_detail_id">
                                            <input type="hidden"
                                                value="<?php echo $processedMealDetail->meal_id ?>"
                                                name="meal_id">
                                            <input class="option" type="submit" value="Eliminar">
                                        </form>
                                    </div>
                                </div>

                            </li>
                    <?php }
                    }; ?>
                </ul>

            </div>

        <?php endforeach; ?>

    <?php } ?>

</div>