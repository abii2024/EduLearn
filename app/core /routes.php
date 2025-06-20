<?php
require_once __DIR__ . '/router.php';

// Controllers importeren
require_once __DIR__ . '/../controllers/HomepageController.php';
require_once __DIR__ . '/../controllers/NewsPageController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
// Voeg eventueel meer controllers toe als je wilt uitbreiden

$router = new Router();

// Homepagina
$router->add('GET', '/', fn() => HomepageController::execute());

// Nieuws
$router->add('GET', '/news', fn() => NewsPageController::execute());

// Login
$router->add('GET', '/login', fn() => AuthController::login());
$router->add('POST', '/login', fn() => AuthController::login());

// Register
$router->add('GET', '/register', fn() => AuthController::register());
$router->add('POST', '/register', fn() => AuthController::register());

// Logout
$router->add('GET', '/logout', fn() => AuthController::logout());

// Dashboard (protected)
$router->add('GET', '/dashboard', fn() => DashboardController::execute());

// (optioneel) Meer routes voor lessen, opdrachten, admin, etc. toevoegen:
# $router->add('GET', '/lessons/create', fn() => LessonController::create());
// etc.

