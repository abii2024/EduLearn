<?php
// app/controllers/ClassController.php
require_once dirname(__DIR__, 2) . '/config/dbConnect.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/User.php';

class ClassController
{
    public static function showClasses()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        $teacherId = $_SESSION['user']['id'];
        $classes = ClassModel::getByTeacher($teacherId);
        
        require_once __DIR__ . '/../views/classes_list.php';
        ClassesListView::Render($classes);
    }

    public static function showCreateClass()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        require_once __DIR__ . '/../views/create_class.php';
        CreateClassView::Render();
    }

    public static function processCreateClass()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $teacherId = $_SESSION['user']['id'];

            if (empty($name)) {
                $error = "Klasnaam is verplicht.";
                require_once __DIR__ . '/../views/create_class.php';
                CreateClassView::Render($error);
                return;
            }

            $class = new ClassModel($name, $description, $teacherId);
            if ($class->save()) {
                header("Location: /EduLearn/public/classes");
                exit;
            } else {
                $error = "Er is een fout opgetreden bij het aanmaken van de klas.";
                require_once __DIR__ . '/../views/create_class.php';
                CreateClassView::Render($error);
            }
        }
    }

    public static function showClassDetails($classId)
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        $class = ClassModel::getById($classId);
        if (!$class || $class['teacher_id'] != $_SESSION['user']['id']) {
            header("Location: /EduLearn/public/classes");
            exit;
        }

        $students = ClassModel::getStudentsInClass($classId);
        $availableStudents = ClassModel::getAvailableStudents($classId);

        require_once __DIR__ . '/../views/class_details.php';
        ClassDetailsView::Render($class, $students, $availableStudents);
    }

    public static function enrollStudent()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classId = $_POST['class_id'] ?? '';
            $studentId = $_POST['student_id'] ?? '';

            if ($classId && $studentId) {
                // Verify the teacher owns the class
                $class = ClassModel::getById($classId);
                if ($class && $class['teacher_id'] == $_SESSION['user']['id']) {
                    ClassModel::enrollStudent($classId, $studentId);
                }
            }

            header("Location: /EduLearn/public/classes/" . $classId);
            exit;
        }
    }

    public static function unenrollStudent()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classId = $_POST['class_id'] ?? '';
            $studentId = $_POST['student_id'] ?? '';

            if ($classId && $studentId) {
                // Verify the teacher owns the class
                $class = ClassModel::getById($classId);
                if ($class && $class['teacher_id'] == $_SESSION['user']['id']) {
                    ClassModel::unenrollStudent($classId, $studentId);
                }
            }

            header("Location: /EduLearn/public/classes/" . $classId);
            exit;
        }
    }

    public static function deleteClass()
    {
        // Session is already started in routing_entry.php
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header("Location: /EduLearn/public/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classId = $_POST['class_id'] ?? '';
            $teacherId = $_SESSION['user']['id'];

            if ($classId) {
                // Verify the teacher owns the class
                $class = ClassModel::getById($classId);
                if ($class && $class['teacher_id'] == $teacherId) {
                    try {
                        ClassModel::deleteClassWithEnrollments($classId);
                        // Success - redirect to classes list
                        header("Location: /EduLearn/public/classes");
                        exit;
                    } catch (Exception $e) {
                        // Error occurred
                        $error = "Er is een fout opgetreden bij het verwijderen van de klas.";
                        // Redirect back with error (for now just redirect)
                        header("Location: /EduLearn/public/classes");
                        exit;
                    }
                }
            }
        }

        header("Location: /EduLearn/public/classes");
        exit;
    }
}
?>
