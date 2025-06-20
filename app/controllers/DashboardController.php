<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/dbConnect.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';

// Views
require_once __DIR__ . '/../views/DashboardStudentView.php';
require_once __DIR__ . '/../views/DashboardTeacherView.php';

class DashboardController extends BaseController
{
    public static function execute()
    {
        $controller = new self();
        $controller->requireLogin();

        $role = $_SESSION['user']['role'];
        $userId = $_SESSION['user']['id'];
        $name = $_SESSION['user']['name'];

        if ($role === 'student') {
            $lessons = Lesson::getAllForStudent($userId);
            $assignments = Assignment::getAllForStudent($userId);
            DashboardStudentView::Render($lessons, $assignments, $name);

        } elseif ($role === 'teacher') {
            $lessons = Lesson::getAllByTeacher($userId);
            $recentAssignments = Assignment::getRecentByTeacher($userId);
            DashboardTeacherView::Render($lessons, $recentAssignments, $name);

        } else {
            header("Location: /");
            exit;
        }
    }
}
