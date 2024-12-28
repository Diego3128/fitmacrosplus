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
//validate if a value is an integer
function validateInteger($value)
{
    if (filter_var(trim($value), FILTER_VALIDATE_INT)) {
        return (int) $value;
    }
    return null; //invalid int
}
//check if the user is an admin
function isAdmin(): void
{
    isAuth();

    if (!isset($_SESSION["admin"])) header("location: /appointment");
}
//redirect to a ceartain endpoint
function redirectTo($path)
{
    header("location: " . $path);
    exit;
}
//format a decimal number by removing unsignificant zeros
function formatDecimal($number)
{
    if (!is_numeric($number)) return $number;

    return ($number == floor($number)) ? sprintf('%d', $number) : sprintf('%s', $number);
    // Example:
    // 100.0// output: 100
    // 100. // output: 100.5
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
