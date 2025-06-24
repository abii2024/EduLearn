<?php
// core/routes.php - Route definitions

// Create router instance
$router = new Router();

// Define routes
$router->get('/', function() {
    require_once BASE_PATH . '/app/controllers/HomepageController.php';
    HomepageController::execute();
});

$router->get('/login', function() {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    AuthController::showLogin();
});

$router->post('/login', function() {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    AuthController::processLogin();
});

$router->get('/register', function() {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    AuthController::showRegister();
});

$router->post('/register', function() {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    AuthController::processRegister();
});

$router->get('/dashboard', function() {
    require_once BASE_PATH . '/app/controllers/DashboardController.php';
    DashboardController::execute();
});

$router->get('/news', function() {
    require_once BASE_PATH . '/app/controllers/NewsPageController.php';
    NewsPageController::execute();
});

$router->get('/logout', function() {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    AuthController::logout();
});

// Class management routes
$router->get('/classes', function() {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::showClasses();
});

$router->get('/classes/create', function() {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::showCreateClass();
});

$router->post('/classes/create', function() {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::processCreateClass();
});

$router->get('/classes/{id}', function($id) {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::showClassDetails($id);
});

$router->post('/classes/enroll', function() {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::enrollStudent();
});

$router->post('/classes/unenroll', function() {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::unenrollStudent();
});

$router->post('/classes/delete', function() {
    require_once BASE_PATH . '/app/controllers/ClassController.php';
    ClassController::deleteClass();
});

// Lesson management routes
$router->get('/lessons/create', function() {
    require_once BASE_PATH . '/app/controllers/LessonController.php';
    LessonController::showCreateLesson();
});

$router->post('/lessons/create', function() {
    require_once BASE_PATH . '/app/controllers/LessonController.php';
    LessonController::processCreateLesson();
});

$router->get('/lessons/{id}', function($id) {
    require_once BASE_PATH . '/app/controllers/LessonController.php';
    LessonController::showLessonDetails($id);
});

// Return router
return $router;
