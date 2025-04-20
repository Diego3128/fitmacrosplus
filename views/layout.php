<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FitMacrosPlus - Controla tus Calorías y Macronutrientes</title>

    <meta name="description" content="FitMacrosPlus es una aplicación web que te ayuda a llevar un control de tus calorías y macronutrientes. Registra tus comidas, sigue tu progreso y mantén una dieta saludable.">

    <meta name="keywords" content="fitmacros, calorías, nutrición, macronutrientes, dieta saludable, seguimiento de comidas, app nutricional">

    <meta name="author" content="Diego3128">

    <meta property="og:title" content="FitMacrosPlus - Controla tus Calorías y Macronutrientes">
    <meta property="og:description" content="Una app para llevar un control preciso de tu dieta y nutrición.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://fitmacrosplus.com">
    <meta property="og:image" content="https://fitmacrosplus.com/assets/images/social-preview.png">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="FitMacrosPlus">
    <meta name="twitter:description" content="Controla tu dieta, calorías y macronutrientes con FitMacrosPlus.">
    <meta name="twitter:image" content="https://fitmacrosplus.com/assets/images/social-preview.png">

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
    <link rel="manifest" href="/assets/icons/site.webmanifest">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/build/css/app.css">
</head>

<body>
    <div class="root">
        <?php echo $content; ?>
    </div>

    <?php
    // scripts sent from the view
    echo $scripts ?? '';
    ?>
</body>

</html>