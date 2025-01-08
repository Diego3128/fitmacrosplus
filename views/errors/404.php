<main class="not-found__container container">
    <p class="not-found__subtitle">Parece que estás perdido...</p>
    <p class="not-found__text">La página que buscas no existe o se ha movido. Pero no te preocupes, ¡te ayudaremos a volver al camino!</p>
    <a href="<?php echo isset($_SESSION["loggedin"]) ? '/home' : '/' ?>"
        class="not-found__btn">
        Volver al inicio
    </a>
    <img src="/build/img/icons/404.svg" alt="Icono 404" class="not-found__image">
</main>