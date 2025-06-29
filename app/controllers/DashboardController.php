<?php
require_once __DIR__ . '/BaseController.php';
require_once dirname(__DIR__) . '/../config/dbConnect.php';

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';

require_once __DIR__ . '/../views/dashboard_student.php';
require_once __DIR__ . '/../views/dashboard_teacher.php';

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
            DashboardStudentView::Render($name, $lessons, $assignments);

        } elseif ($role === 'teacher') {
            $lessons = Lesson::getAllByTeacher($userId);
            $recentAssignments = Assignment::getRecentByTeacher($userId);
            DashboardTeacherView::Render($name, $lessons, $recentAssignments);

        } elseif ($role === 'admin') {
            header("Location: /EduLearn/public/admin");
            exit;

        } else {
            header("Location: /EduLearn/public/");
            exit;
        }
    }
}
