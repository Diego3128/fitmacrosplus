<nav class="navbar">

    <div class="container flex-center">

        <a href="/" class="logo"><img src="/build/img/logo/logo-white.svg" alt="logo"></a>

        <div class="dropdown-container">
            <div class="dropdown flex-center">
                <button class="dropdown-button"><img src="/build/img/icons/on.svg" alt="login options"></button>
                <div class="dropdown-content">
                    <!-- to do: HIDE WHEN A SESSION IS STARTED -->
                    <a href="/login">Iniciar sesión</a>
                    <a href="/register">Registrarse</a>
                    <!-- to do: ONLY SHOW THE FOLLOWING WHEN A SESSION IS STARTED  -->
                    <a href="/logout">Cerrar sesión</a>
                </div>
            </div>

            <?php if (isset($username)): ?>
                <div class="user-info">
                    <p><?php echo $username; ?></p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</nav>