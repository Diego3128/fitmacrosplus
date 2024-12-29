<section class="card card--todays-meals">
    <h2 class="card__title">Resumen del día</h2>
    <?php if (isset($userMeals) and isset($mealStats)): ?>
        <?php foreach ($userMeals as $userMeal): ?>
            <li class="meal-list__item">
                <span class="meal-list__icon meal-list__icon--breakfast"></span>
                <span class="meal-list__name"><?php echo escapeHTML($userMeal->name); ?></span>
                <span class="meal-list__calories"><?php echo $mealStats[$userMeal->name]["calories"] ?? 0 ?> kcal</span>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
    </ul>
</section>