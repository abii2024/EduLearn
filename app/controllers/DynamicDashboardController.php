<?php
require_once dirname(__DIR__) . '/../config/dbConnect.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/ClassModel.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Assignment.php';
require_once __DIR__ . '/../views/dynamic_dashboard.php';

class DynamicDashboardController
{
    public static function execute()
    {
        // Verzamel data op basis van gebruikersrol
        $data = [];
        
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $userRole = $_SESSION['user']['role'];
            
            // Verzamel role-specifieke data
            switch ($userRole) {
                case 'student':
                    $data = self::getStudentData($userId);
                    break;
                case 'teacher':
                    $data = self::getTeacherData($userId);
                    break;
                case 'admin':
                    $data = self::getAdminData();
                    break;
            }
        }
        
        // Render de dynamische dashboard view
        DynamicDashboardView::Render($data);
    }
    
    /**
     * Verzamel data voor studenten
     */
    private static function getStudentData($studentId)
    {
        try {
            // Klassen waar student is ingeschreven
            $classes = ClassModel::getClassesForStudent($studentId);
            
            // Lessen voor student
            $lessons = Lesson::getAllForStudent($studentId);
            
            // Opdrachten voor student
            $assignments = Assignment::getAllForStudent($studentId);
            
            return [
                'classes' => $classes,
                'lessons' => $lessons,
                'assignments' => $assignments
            ];
        } catch (Exception $e) {
            return [
                'classes' => [],
                'lessons' => [],
                'assignments' => []
            ];
        }
    }
    
    /**
     * Verzamel data voor docenten
     */
    private static function getTeacherData($teacherId)
    {
        try {
            // Klassen van docent
            $classes = ClassModel::getByTeacher($teacherId);
            
            // Lessen van docent
            $lessons = Lesson::getAllByTeacher($teacherId);
            
            // Tel totaal aantal studenten
            $totalStudents = 0;
            foreach ($classes as $class) {
                $students = ClassModel::getClassStudents($class['id']);
                $totalStudents += count($students);
            }
            
            return [
                'classes' => $classes,
                'lessons' => $lessons,
                'total_students' => $totalStudents
            ];
        } catch (Exception $e) {
            return [
                'classes' => [],
                'lessons' => [],
                'total_students' => 0
            ];
        }
    }
    
    /**
     * Verzamel data voor administrators
     */
    private static function getAdminData()
    {
        try {
            // Totaal aantal gebruikers
            $totalUsers = count(User::findAll());
            
            // Alle klassen
            $classes = ClassModel::findAll();
            
            // Systeem statistieken
            $stats = [
                'total_users' => $totalUsers,
                'total_classes' => count($classes),
                'system_status' => 'operational'
            ];
            
            return array_merge($stats, [
                'classes' => $classes
            ]);
        } catch (Exception $e) {
            return [
                'total_users' => 0,
                'classes' => [],
                'system_status' => 'error'
            ];
        }
    }
}
