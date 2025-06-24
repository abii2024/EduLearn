<?php
// app/controllers/LessonController.php
require_once dirname(__DIR__, 2) . '/config/dbConnect.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/ClassModel.php';

class LessonController
{
    public static function showCreateLesson()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        $teacherId = $_SESSION['user']['id'];
        $classes = ClassModel::getByTeacher($teacherId);

        require_once __DIR__ . '/../views/create_lesson.php';
        CreateLessonView::Render($classes);
    }

    public static function processCreateLesson()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $classId = $_POST['class_id'] ?? '';
            $teacherId = $_SESSION['user']['id'];

            if (empty($title) || empty($classId)) {
                $error = "Titel en klas zijn verplicht.";
                $classes = ClassModel::getByTeacher($teacherId);
                require_once __DIR__ . '/../views/create_lesson.php';
                CreateLessonView::Render($classes, $error);
                return;
            }

            // Verify the teacher owns the selected class
            $class = ClassModel::getById($classId);
            if (!$class || $class['teacher_id'] != $teacherId) {
                $error = "Ongeldige klas geselecteerd.";
                $classes = ClassModel::getByTeacher($teacherId);
                require_once __DIR__ . '/../views/create_lesson.php';
                CreateLessonView::Render($classes, $error);
                return;
            }

            $lesson = new Lesson($teacherId, $title, $description, $classId);
            if ($lesson->save()) {
                header("Location: /EduLearn/public/dashboard");
                exit;
            } else {
                $error = "Er is een fout opgetreden bij het aanmaken van de les.";
                $classes = ClassModel::getByTeacher($teacherId);
                require_once __DIR__ . '/../views/create_lesson.php';
                CreateLessonView::Render($classes, $error);
            }
        }
    }

    public static function showLessonDetails($lessonId)
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user'])) {
            header("Location: /EduLearn/public/login");
            exit;
        }

        // Get lesson details (implementation would depend on requirements)
        // For now, redirect to dashboard
        header("Location: /EduLearn/public/dashboard");
        exit;
    }
}
?>
