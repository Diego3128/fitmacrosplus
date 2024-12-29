<section class="card card--nutrient-breakdown">
    <h2 class="card__title">Macros</h2>
    <ul class="card__list nutrient-list">

        <li class="nutrient-list__item">
            <span class="nutrient-list__name">Proteina</span>
            <div class="nutrient-list__progress-bar">
                <div class="nutrient-list__progress-fill"
                    style="width: <?php if (isset($statPercentages)) echo $statPercentages["proteinProgress"] ?? 0 ?>%;">
                </div>
            </div>
            <span class="nutrient-list__value">
                <?php if (isset($generalStats)) echo $generalStats["totalProtein"] ?? 0; ?>
                /
                <?php echo $userRequirement->protein_requirement ?>g
            </span>
        </li>

        <li class="nutrient-list__item">
            <span class="nutrient-list__name">Carbs</span>
            <div class="nutrient-list__progress-bar">
                <div class="nutrient-list__progress-fill"
                    style="width: <?php if (isset($statPercentages)) echo $statPercentages["carbsProgress"] ?? 0 ?>%;">
                </div>
            </div>
            <span class="nutrient-list__value">
                <?php if (isset($generalStats)) echo $generalStats["totalCarbs"] ?? 0; ?>
                /
                <?php echo $userRequirement->carb_requirement ?>g</span>
        </li>

        <li class="nutrient-list__item">
            <span class="nutrient-list__name">Grasa</span>
            <div class="nutrient-list__progress-bar">
                <div class="nutrient-list__progress-fill"
                    style="width: <?php if (isset($statPercentages)) echo $statPercentages["fatProgress"] ?? 0 ?>%;">
                </div>
            </div>
            <span class="nutrient-list__value">
                <?php if (isset($generalStats)) echo $generalStats["totalFat"] ?? 0; ?>
                /
                <?php echo escapeHTML($userRequirement->fat_requirement) ?>g</span>
        </li>

    </ul>
</section>