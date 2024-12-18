<?php
// settings, variables and constant variables
define('FUNCTIONS_URL', __DIR__ . '\\functions.php');
define('IMAGES_DIR', $_SERVER["DOCUMENT_ROOT"] . '/images/');

function debugAndFormat($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    exit;
}
//escape HTML when doing echo
function escapeHTML(string | null $html): string
{
    if (is_null($html)) {
        $html = '';
    }
    $s = htmlspecialchars($html);
    return $s;
}
//check if the user is authenticated
function isAuth()
{
    if (!isset($_SESSION["loggedin"])) {
        header("location: /");
        exit;
    }
}
//check if the user is an admin
function isAdmin(): void
{
    isAuth();

    if (!isset($_SESSION["admin"])) header("location: /appointment");
}
//block public forms if the user is authenticated
function redirectToHomeIfLoggedIn(): void
{
    if (isset($_SESSION["loggedin"])) {
        header("location: /home");
        exit;
    }
}
function blockLoginRegister(): void
{
    if (isset($_SESSION["loggedin"])) {
        header("location: /home");
        exit;
    }
}

function getErrorMessage($code): string | null
{
    $errors = [
        1 => "Error al enviar email con el token. Pidelo de nuevo!",
        2 => "Error al crear cuenta, inténtalo de nuevo más tarde",
        3 => "Error de conexión a la base de datos, inténtalo de nuevo más tarde",
        4 => "Error: la solicitud no es válida",
        5 => "Error: el token de validación no es correcto o ha expirado",
        6 => "Error desconocido, inténtalo de nuevo más tarde"
    ];

    // Devolver el mensaje si existe el código, de lo contrario devolver un mensaje por defecto
    return $errors[$code] ?? null;
}
