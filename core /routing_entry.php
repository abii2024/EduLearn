<?php
// core/routing_entry.php

// Define security constant
define('rinder', true);

// Start sessie als dat nog niet is gedaan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zet de juiste base path voor includes
define('BASE_PATH', dirname(__DIR__));

// Load helper functions
require_once BASE_PATH . '/core /helpers.php';

// ✅ Voeg de databaseverbinding toe
require_once BASE_PATH . '/config/dbConnect.php';

// ✅ Models laden
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Student.php';
require_once BASE_PATH . '/app/models/Teacher.php';
require_once BASE_PATH . '/app/models/Lesson.php';
require_once BASE_PATH . '/app/models/Assignment.php';
require_once BASE_PATH . '/app/models/ClassModel.php';
require_once BASE_PATH . '/app/models/SalesModel.php';
require_once BASE_PATH . '/app/models/NewsModel.php';

// ✅ Database tabellen initialiseren
try {
    User::initializeDatabase();
    Student::initializeDatabase();
    Teacher::initializeDatabase();
    Lesson::initializeDatabase();
    Assignment::initializeDatabase();
    SalesModel::initializeDatabase();
    NewsModel::initializeDatabase();
} catch (Exception $e) {
    // Stille fout - tabellen bestaan waarschijnlijk al
}

// Router en routes laden
require_once BASE_PATH . '/core /router.php';
$router = require BASE_PATH . '/core /routes.php';

// Bepaal request method en URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip de /EduLearn/public prefix voor XAMPP
$baseFolder = '/EduLearn/public';
if (str_starts_with($uri, $baseFolder)) {
    $uri = substr($uri, strlen($baseFolder));
    if ($uri === '') $uri = '/';
}

// Dispatch het request!
$router->dispatch($method, $uri);
