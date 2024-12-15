<fieldset>
    <legend class="text-center">Información Personal</legend>

    <label for="name">Nombre</label>
    <input
        type="text"
        id="name"
        name="user[name]"
        value="<?php echo escapeHTML($user->name); ?>"
        placeholder="Ingresa tu nombre"
        required
        maxlength="50"
        minlength="3">

    <label for="lastname">Apellido</label>
    <input
        type="text"
        id="lastname"
        name="user[lastname]"
        value="<?php echo escapeHTML($user->lastname); ?>"
        placeholder="Ingresa tu apellido"
        required maxlength="50"
        minlength="3">

    <label for="email">Correo Electrónico</label>
    <input
        type="email"
        id="email"
        name="user[email]"
        value="<?php echo escapeHTML($user->email); ?>"
        placeholder="ejemplo@email.com"
        required
        maxlength="80">

    <label for="password">Contraseña</label>
    <input
        type="password"
        id="password"
        name="user[password]"
        placeholder="Crea una contraseña"
        required
        maxlength="50"
        minlength="6">
</fieldset>

<div class="form-footer">
    <button type="submit" class="btn btn-submit"><span class="text">Registrarse</span></button>
</div>

<div class="form-options">
    <a href="/login">¿Ya tienes una cuenta?</a>
</div>