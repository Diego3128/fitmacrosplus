<fieldset>
    <legend class="text-center">Nombre de la comida</legend>
    <!-- Age -->
    <label for="meal-name" class="icon-label "> Nombre</label>
    <input type="text" id="meal-name" name="meal[name]" min="1" max="50" placeholder="Ej: Snack" required value="<?php echo escapeHTML($userMeal->name); ?>">

</fieldset>