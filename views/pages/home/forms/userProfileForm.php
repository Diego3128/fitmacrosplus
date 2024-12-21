<fieldset>
    <legend class="text-center">Información para perzonalizar tu perfil</legend>
    <!-- Age -->
    <label for="age" class="icon-label icon-label--age"> Edad</label>
    <input type="number" id="age" name="user[age]" min="10" max="120" placeholder="Ej: 28" required value="<?php echo escapeHTML($userProfile->age); ?>">

    <!-- Weight -->
    <label for="weight" class="icon-label icon-label--weight">Peso (kg)</label>
    <input type="number" id="weight" name="user[weight]" step="0.01" min="20" max="500" required placeholder="Ej: 80" value="<?php echo escapeHTML($userProfile->weight); ?>">

    <!-- Height -->
    <label for="height" class="icon-label icon-label--height"> Altura (cm)</label>
    <input type="number" id="height" name="user[height]" step="0.01" required min="50" max="300" placeholder="Ej: 175" value="<?php echo escapeHTML($userProfile->height); ?>">

    <!-- Gender -->
    <label for="gender" class="icon-label icon-label--gender">Genero</label>
    <select id="gender" name="user[gender_id]" class="select" required>
        <option value="" disabled selected>Selecciona tu genero</option>
        <?php if (isset($genders)): ?>
            <?php foreach ($genders as $gender) { ?>
                <option
                    <?php echo $gender->id === $userProfile->gender_id ? 'selected' : '' ?>
                    value="<?php echo escapeHTML($gender->id); ?>">
                    <?php echo escapeHTML($gender->name); ?>
                </option>
            <?php } ?>
        <?php endif; ?>
    </select>
    <!-- Activity Level -->
    <label for="activity_level" class="icon-label icon-label--activity"> Nivel de actividad fisica</label>
    <select id="activity_level" name="user[activity_level_id]" class="select" required>
        <option value="" disabled selected>Selecciona tu nivel</option>
        <?php if (isset($activityLevels)): ?>
            <?php foreach ($activityLevels as $acitivityLevel) { ?>
                <option
                    <?php echo $acitivityLevel->id === $userProfile->activity_level_id ? 'selected' : '' ?>
                    value="<?php echo escapeHTML($acitivityLevel->id); ?>">
                    <?php echo escapeHTML($acitivityLevel->name); ?>
                </option>
            <?php } ?>
        <?php endif; ?>
    </select>
    <!-- Goal -->
    <label for="goal" class="icon-label icon-label--intent">Tu meta o proposito</label>
    <select id="goal" name="user[goal_id]" class="select" required>
        <option value="" disabled selected>Selecciona tu meta</option>
        <?php if (isset($goals)): ?>
            <?php foreach ($goals as $goal) { ?>
                <option
                    <?php echo $goal->id === $userProfile->goal_id ? 'selected' : '' ?>
                    value="<?php echo escapeHTML($goal->id); ?>">
                    <?php echo escapeHTML($goal->name); ?>
                </option>
            <?php } ?>
        <?php endif; ?>
    </select>
</fieldset>