<!-- Primera parte: Campos visibles por defecto -->
<fieldset class="food-creation__fieldset">
    <legend class="food-creation__legend text-center">Información básica</legend>

    <div class="food-creation__field flex">
        <label for="food-name" class="food-creation__label">Nombre del alimento</label>
        <input maxlength="50" required type="text" id="food-name" name="food[name]" class="food-creation__input name"
            value="<?php echo escapeHTML($userFood->name); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-brand" class="food-creation__label">Marca</label>
        <input maxlength="50" required type="text" id="food-brand" name="food[brand]" class="food-creation__input brand" placeholder="opcional"
            value="<?php echo escapeHTML($userFood->brand); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-serving_size" class="food-creation__label">Tamaño de la porción</label>
        <input maxlength="8" required type="number" step="1" id="food-serving_size" name="food[serving_size]" class="food-creation__input" required value="<?php echo escapeHTML($userFood->serving_size); ?>">
    </div>

    <div class="food-creation__field flex unit">
        <label for="food-serving_unit_id" class="food-creation__label">Unidad de porción</label>
        <select id="food-serving_unit_id" name="food[serving_unit_id]" class="select food-creation__select" required>
            <option disabled selected>elegir</option>
            <?php foreach ($units as $unit) : ?>
                <option <?php echo $userFood->serving_unit_id === $unit->id ? 'selected' : '' ?>
                    value="<?php echo escapeHTML($unit->id); ?>">
                    <?php echo escapeHTML($unit->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="food-creation__field flex">
        <label for="food-calories" class="food-creation__label">Calorías</label>
        <input disabled type="number" id="food-calories" name="food[calories]" class="food-creation__input calories"
            value="<?php echo escapeHTML($userFood->calories); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-fat" class="food-creation__label">Grasas(g)</label>
        <input maxlength="8" required type="number" step="0.1" id="food-fat" name="food[fat]" class="food-creation__input"
            value="<?php echo escapeHTML($userFood->fat); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-saturated" class="food-creation__label">grasas saturadas(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-saturated" name="food[saturated]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->saturated); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-polyunsaturated" class="food-creation__label">grasas poliinsaturadas(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-polyunsaturated" name="food[polyunsaturated]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->polyunsaturated); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-monounsaturated" class="food-creation__label">grasas monoinsaturadas(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-monounsaturated" name="food[monounsaturated]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->monounsaturated); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-trans" class="food-creation__label">grasas trans(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-trans" name="food[trans]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->trans); ?>">
    </div>
    <div class="food-creation__field flex">
        <label for="food-carbohydrate" class="food-creation__label">carbohidratos(g)</label>
        <input maxlength="8" required type="number" step="0.1" id="food-carbohydrate" name="food[carbohydrate]" class="food-creation__input"
            value="<?php echo escapeHTML($userFood->carbohydrate); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-fiber" class="food-creation__label pdf">fibra(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-fiber" name="food[fiber]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->fiber); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-sugars" class="food-creation__label pdf">azúcar(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-sugars" name="food[sugars]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->sugars); ?>">
    </div>

    <div class="food-creation__field flex pdf">
        <label for="food-sugar_alcohols" class="food-creation__label pdf">alcoholes de azucar</label>
        <input maxlength="8" type="number" step="0.1" id="food-sugar_alcohols" name="food[sugar_alcohols]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->sugar_alcohols); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-protein" class="food-creation__label">proteina(g)</label>
        <input maxlength="8" required type="number" step="0.1" id="food-protein" name="food[protein]" class="food-creation__input"
            value="<?php echo escapeHTML($userFood->protein); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-cholesterol" class="food-creation__label">colesterol(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-cholesterol" name="food[cholesterol]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->cholesterol); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-salt" class="food-creation__label">sal(g)</label>
        <input maxlength="8" type="number" step="0.1" id="food-salt" name="food[salt]" class="food-creation__input" placeholder="opcional"
            value="<?php echo escapeHTML($userFood->salt); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-sodium" class="food-creation__label">sodio(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-sodium" name="food[sodium]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->sodium); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-potassium" class="food-creation__label">potasio(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-potassium" name="food[potassium]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->potassium); ?>">
    </div>

    <!-- button to show second part of the form -->
    <button type="button" class="food-creation__toggle" id="toggle-advanced">
        Más opciones
    </button>
</fieldset>

<fieldset class="food-creation__fieldset food-creation__fieldset--hidden" id="advanced-fields">
    <legend class="food-creation__legend text-center">Campos adicionales</legend>


    <div class="food-creation__field flex">
        <label for="food-calcium" class="food-creation__label">calcio(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-calcium" name="food[calcium]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->calcium); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-copper" class="food-creation__label">cobre(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-copper" name="food[copper]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->copper); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-iron" class="food-creation__label">hierro(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-iron" name="food[iron]" class="food-creation__input" placeholder="opcional"
            value="<?php echo escapeHTML($userFood->iron); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-magnesium" class="food-creation__label">magnesio(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-magnesium" name="food[magnesium]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->magnesium); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-manganese" class="food-creation__label">manganeso(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-manganese" name="food[manganese]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->manganese); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-phosphorus" class="food-creation__label">fósforo(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-phosphorus" name="food[phosphorus]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->phosphorus); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-selenium" class="food-creation__label">selenio(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-selenium" name="food[selenium]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->selenium); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-zinc" class="food-creation__label">zinc(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-zinc" name="food[zinc]" class="food-creation__input" placeholder="opcional"
            value="<?php echo escapeHTML($userFood->zinc); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_a" class="food-creation__label">vitamina a(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_a" name="food[vitamin_a]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_a); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b1" class="food-creation__label">vitamina b1(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b1" name="food[vitamin_b1]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b1); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b2" class="food-creation__label">vitamina b2(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b2" name="food[vitamin_b2]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b2); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b3" class="food-creation__label">vitamina b3(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b3" name="food[vitamin_b3]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b3); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b5" class="food-creation__label">vitamina b5(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b5" name="food[vitamin_b5]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b5); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b6" class="food-creation__label">vitamina b6(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b6" name="food[vitamin_b6]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b6); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b11" class="food-creation__label">vitamina b11(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b11" name="food[vitamin_b11]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b11); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_b12" class="food-creation__label">vitamina b12(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_b12" name="food[vitamin_b12]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_b12); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_c" class="food-creation__label">vitamina c(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_c" name="food[vitamin_c]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_c); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_e" class="food-creation__label">vitamina e(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_e" name="food[vitamin_e]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_e); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_d" class="food-creation__label">vitamina d(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_d" name="food[vitamin_d]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_d); ?>">
    </div>

    <div class="food-creation__field flex">
        <label for="food-vitamin_k" class="food-creation__label">vitamina k(mg)</label>
        <input maxlength="8" type="number" step="0.1" id="food-vitamin_k" name="food[vitamin_k]" class="food-creation__input" placeholder="opcional" value="<?php echo escapeHTML($userFood->vitamin_k); ?>">
    </div>

</fieldset>