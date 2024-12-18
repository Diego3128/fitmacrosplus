<fieldset>
    <legend class="text-center">Información para perzonalizar tu perfil</legend>
    <!-- Age -->
    <label for="age" class="icon-label icon-label--age"> Edad</label>
    <input type="number" id="age" name="age" min="1" max="120" placeholder="Ej: 28" required>

    <!-- Weight -->
    <label for="weight" class="icon-label icon-label--weight">Peso (kg)</label>
    <input type="number" id="weight" name="weight" step="0.01" placeholder="Ej: 80" required>

    <!-- Height -->
    <label for="height" class="icon-label icon-label--height"> Altura (cm)</label>
    <input type="number" id="height" name="height" step="0.01" placeholder="Ej: 175" required>

    <!-- Gender -->
    <label for="gender" class="icon-label icon-label--gender">Genero</label>
    <select id="gender" name="gender_id" required class="select">
        <option value="" disabled selected>Selecciona tu genero</option>
        <option value="1">Masculino</option>
        <option value="2">Femenino</option>
    </select>
    <!-- Activity Level -->
    <label for="activity_level" class="icon-label icon-label--activity"> Nivel de actividad fisica</label>
    <select id="activity_level" name="activity_level_id" class="select" required>
        <option value="" disabled selected>Selecciona tu nivel</option>
        <option value="1">Sedentario</option>
        <option value="2">Ligeramente activo</option>
        <option value="3">Ejercicio moderado</option>
        <option value="4">Muy activo</option>
    </select>
    <!-- Goal -->
    <label for="goal" class="icon-label icon-label--intent">Tu meta o proposito</label>
    <select id="goal" name="goal_id" required class="select">
        <option value="" disabled selected>Selecciona tu meta</option>
        <option value="1">Perder peso</option>
        <option value="2">Mantener mi peso</option>
        <option value="3">Ganar musculo</option>
    </select>
</fieldset>