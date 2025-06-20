<?php
// Start sessie als dat nog niet is gedaan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zet de juiste base path voor includes
define('BASE_PATH', dirname(dirname(__DIR__)));

// Router en routes laden
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/routes.php';

// Bepaal request method en URI
$method = $_SERVER['REQUEST_METHOD'];
// Haal het path-gedeelte van de URL zonder querystring
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip eventueel de /public prefix uit de URL (voor development in XAMPP)
$baseFolder = '/public';
if (str_starts_with($uri, $baseFolder)) {
    $uri = substr($uri, strlen($baseFolder));
    if ($uri === '') $uri = '/';
}

// Dispatch het request!
$router->dispatch($method, $uri);
