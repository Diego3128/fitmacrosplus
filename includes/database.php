<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $db = new mysqli(
        $_ENV["DB_HOST"],
        $_ENV["DB_USERNAME"],
        $_ENV["DB_PASSWORD"],
        $_ENV["DB_NAME"]
    );
    $db->set_charset("utf8");

    // $query = $db->query("SELECT 1 + 1 AS 'WORKING'");
    // debugAndFormat($query->fetch_assoc());
} catch (mysqli_sql_exception $e) {
    // debugAndFormat($e);
    exit("Error trying to connect to db");
}
