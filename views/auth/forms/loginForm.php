<fieldset>
    <legend class="text-center">Ingresa tus datos</legend>

    <label for="email">Correo Electrónico</label>
    <input
        type="email"
        id="email"
        name="user[email]"
        value="<?php echo escapeHTML($user->email); ?>"
        placeholder="Ingresa tu correo electrónico"
        required
        maxlength="80">

    <label for="password">Contraseña</label>
    <input
        type="password"
        id="password"
        name="user[password]"
        placeholder="Ingresa tu contraseña"
        required
        maxlength="50"
        minlength="6">
</fieldset>

<div class="form-footer">
    <button type="submit" class="btn btn-submit"><span class="text">Iniciar sesión</span></button>
</div>

<div class="form-options">
    <a href="/password/request">¿Olvidaste tu contraseña?</a>
    <a href="/register">¿No tienes una cuenta?</a>
</div>