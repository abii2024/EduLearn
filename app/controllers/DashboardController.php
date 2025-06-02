<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/dbConnect.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';

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
            $studentName = $name;
            include_once __DIR__ . '/../views/dashboard_student.php';

        } elseif ($role === 'teacher') {
            $lessons = Lesson::getAllByTeacher($userId);
            $recentAssignments = Assignment::getRecentByTeacher($userId);
            $teacherName = $name;
            include_once __DIR__ . '/../views/dashboard_teacher.php';

        } else {
            header("Location: /");
            exit;
        }
    }
}
