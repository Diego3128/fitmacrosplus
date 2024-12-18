<nav class="navbar">

    <div class="container flex-center">

        <a href="/" class="logo"><img src="/build/img/logo/logo-white.svg" alt="logo"></a>

        <div class="dropdown-container">
            <div class="dropdown flex-center">
                <button class="dropdown-button"><img src="/build/img/icons/on.svg" alt="login options"></button>
                <div class="dropdown-content">
                    <?php if (isset($_SESSION["loggedin"])) { ?>
                        <!-- Show when a user is loggedin  -->
                        <a href="/logout">Cerrar sesión</a>
                    <?php } else { ?>
                        <!--  hide otherwise -->
                        <a href="/login">Iniciar sesión</a>
                        <a href="/register">Registrarse</a>
                    <?php } ?>
                </div>
            </div>

            <?php if (isset($_SESSION["loggedin"])): ?>
                <div class="user-info">
                    <p><?php echo $_SESSION["name"]; ?></p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</nav>