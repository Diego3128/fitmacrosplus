<section class="card card--daily-progress">
    <h2 class="card__title">Progreso</h2>
    <div class="card__content card__content--progress">
        <span class="progress-percentage">
            <?php if (isset($statPercentages)) echo $statPercentages["calorieProgress"] ?? '0'; ?>
            %</span>
        <p class="progress-text">
            <?php if (isset($generalStats)) echo $generalStats["totalCalories"] ?? '0'; ?> kcal/
            <?php echo $userRequirement->caloric_requirement ?? '' ?> kcal
        </p>
    </div>
</section>