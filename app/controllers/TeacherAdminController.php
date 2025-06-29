<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';

class TeacherAdminController extends BaseController
{
    public function showStudentManagement()
    {
        $this->requireLogin();
        $this->requireRole('teacher');
        
        // Get all students
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'student' ORDER BY name ASC");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get teacher's classes for enrollment management
        require_once __DIR__ . '/../models/ClassModel.php';
        $teacherClasses = ClassModel::getByTeacher($_SESSION['user']['id']);
        
        include __DIR__ . '/../views/teacher_admin_students.php';
    }
    
    public function createStudent()
    {
        $this->requireLogin();
        $this->requireRole('teacher');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            
            // Input validation
            if (empty($name) || strlen($name) < 2) {
                $_SESSION['error'] = "Naam moet ten minste 2 karakters bevatten.";
                header("Location: /EduLearn/public/teacher-admin");
                exit;
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Voer een geldig e-mailadres in.";
                header("Location: /EduLearn/public/teacher-admin");
                exit;
            }
            
            if (empty($password) || strlen($password) < 6) {
                $_SESSION['error'] = "Wachtwoord moet ten minste 6 karakters bevatten.";
                header("Location: /EduLearn/public/teacher-admin");
                exit;
            }
            
            // Check if email already exists
            if (User::findByEmail($email)) {
                $_SESSION['error'] = "E-mailadres is al in gebruik.";
                header("Location: /EduLearn/public/teacher-admin");
                exit;
            }
            
            // Create student account
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $student = new Student($name, $email, $hashedPassword);
            
            if ($student->save()) {
                $_SESSION['success'] = "Student account succesvol aangemaakt voor {$name}.";
            } else {
                $_SESSION['error'] = "Fout bij het aanmaken van student account.";
            }
        }
        
        header("Location: /EduLearn/public/teacher-admin");
        exit;
    }
    
    public function enrollStudentInClass()
    {
        $this->requireLogin();
        $this->requireRole('teacher');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = filter_var($_POST['student_id'] ?? '', FILTER_VALIDATE_INT);
            $classId = filter_var($_POST['class_id'] ?? '', FILTER_VALIDATE_INT);
            
            if ($studentId && $classId) {
                // Verify that the class belongs to this teacher
                require_once __DIR__ . '/../models/ClassModel.php';
                $classData = ClassModel::getById($classId);
                
                if ($classData && $classData['teacher_id'] == $_SESSION['user']['id']) {
                    if (ClassModel::enrollStudent($classId, $studentId)) {
                        $_SESSION['success'] = "Student succesvol ingeschreven in de klas.";
                    } else {
                        $_SESSION['error'] = "Fout bij het inschrijven van student.";
                    }
                } else {
                    $_SESSION['error'] = "Je kunt alleen studenten inschrijven in je eigen klassen.";
                }
            }
        }
        
        header("Location: /EduLearn/public/teacher-admin");
        exit;
    }
    
    public function removeStudentFromClass()
    {
        $this->requireLogin();
        $this->requireRole('teacher');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = filter_var($_POST['student_id'] ?? '', FILTER_VALIDATE_INT);
            $classId = filter_var($_POST['class_id'] ?? '', FILTER_VALIDATE_INT);
            
            if ($studentId && $classId) {
                // Verify that the class belongs to this teacher
                require_once __DIR__ . '/../models/ClassModel.php';
                $classData = ClassModel::getById($classId);
                
                if ($classData && $classData['teacher_id'] == $_SESSION['user']['id']) {
                    if (ClassModel::unenrollStudent($classId, $studentId)) {
                        $_SESSION['success'] = "Student succesvol uitgeschreven uit de klas.";
                    } else {
                        $_SESSION['error'] = "Fout bij het uitschrijven van student.";
                    }
                } else {
                    $_SESSION['error'] = "Je kunt alleen studenten uitschrijven uit je eigen klassen.";
                }
            }
        }
        
        header("Location: /EduLearn/public/teacher-admin");
        exit;
    }
    
    public function resetStudentPassword()
    {
        $this->requireLogin();
        $this->requireRole('teacher');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = filter_var($_POST['student_id'] ?? '', FILTER_VALIDATE_INT);
            $newPassword = $_POST['new_password'] ?? '';
            
            if ($studentId && !empty($newPassword) && strlen($newPassword) >= 6) {
                global $pdo;
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, ['cost' => 12]);
                
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ? AND role = 'student'");
                if ($stmt->execute([$hashedPassword, $studentId])) {
                    $_SESSION['success'] = "Wachtwoord succesvol gereset voor student.";
                } else {
                    $_SESSION['error'] = "Fout bij het resetten van wachtwoord.";
                }
            } else {
                $_SESSION['error'] = "Ongeldig wachtwoord. Minimaal 6 karakters vereist.";
            }
        }
        
        header("Location: /EduLearn/public/teacher-admin");
        exit;
    }
}
