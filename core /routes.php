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

// Admin routes
$router->get('/admin', function() {
    require_once BASE_PATH . '/app/controllers/AdminController.php';
    $controller = new AdminController();
    $controller->showDashboard();
});

$router->get('/admin/users', function() {
    require_once BASE_PATH . '/app/controllers/AdminController.php';
    $controller = new AdminController();
    $controller->showUsers();
});

$router->post('/admin/promote', function() {
    require_once BASE_PATH . '/app/controllers/AdminController.php';
    $controller = new AdminController();
    $controller->promoteToAdmin();
});

$router->post('/admin/delete-user', function() {
    require_once BASE_PATH . '/app/controllers/AdminController.php';
    $controller = new AdminController();
    $controller->deleteUser();
});

// Teacher Admin routes (for teachers to manage students)
$router->get('/teacher-admin', function() {
    require_once BASE_PATH . '/app/controllers/TeacherAdminController.php';
    $controller = new TeacherAdminController();
    $controller->showStudentManagement();
});

$router->post('/teacher-admin/create-student', function() {
    require_once BASE_PATH . '/app/controllers/TeacherAdminController.php';
    $controller = new TeacherAdminController();
    $controller->createStudent();
});

$router->post('/teacher-admin/enroll-in-class', function() {
    require_once BASE_PATH . '/app/controllers/TeacherAdminController.php';
    $controller = new TeacherAdminController();
    $controller->enrollStudentInClass();
});

$router->post('/teacher-admin/remove-from-class', function() {
    require_once BASE_PATH . '/app/controllers/TeacherAdminController.php';
    $controller = new TeacherAdminController();
    $controller->removeStudentFromClass();
});

$router->post('/teacher-admin/reset-password', function() {
    require_once BASE_PATH . '/app/controllers/TeacherAdminController.php';
    $controller = new TeacherAdminController();
    $controller->resetStudentPassword();
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
