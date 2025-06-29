<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/User.php';

class AdminController extends BaseController
{
    public function showDashboard()
    {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $allUsers = User::findAll();
        $totalUsers = count($allUsers);
        $studentCount = count(array_filter($allUsers, function($user) { return $user['role'] === 'student'; }));
        $teacherCount = count(array_filter($allUsers, function($user) { return $user['role'] === 'teacher'; }));
        $adminCount = count(array_filter($allUsers, function($user) { return $user['role'] === 'admin'; }));
        
        include __DIR__ . '/../views/admin_dashboard.php';
    }
    
    public function showUsers()
    {
        $this->requireLogin();
        $this->requireRole('admin');
        
        $users = User::findAll();
        include __DIR__ . '/../views/admin_users.php';
    }
    
    public function promoteToAdmin()
    {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $userId = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
            
            if ($userId) {
                global $pdo;
                $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
                if ($stmt->execute([$userId])) {
                    $_SESSION['success'] = "Gebruiker is succesvol gepromoveerd tot administrator.";
                } else {
                    $_SESSION['error'] = "Fout bij het promoveren van gebruiker.";
                }
            }
        }
        
        header("Location: /EduLearn/public/admin/users");
        exit;
    }
    
    public function deleteUser()
    {
        $this->requireLogin();
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $userId = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
            
            if ($userId && $userId != $_SESSION['user']['id']) { // Can't delete self
                global $pdo;
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                if ($stmt->execute([$userId])) {
                    $_SESSION['success'] = "Gebruiker is succesvol verwijderd.";
                } else {
                    $_SESSION['error'] = "Fout bij het verwijderen van gebruiker.";
                }
            }
        }
        
        header("Location: /EduLearn/public/admin/users");
        exit;
    }
}
