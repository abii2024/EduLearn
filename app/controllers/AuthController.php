<?php
require_once __DIR__ . '/../models/dbConnect.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';

// Views
require_once __DIR__ . '/../views/LoginView.php';
require_once __DIR__ . '/../views/RegisterView.php';

class AuthController
{
    public static function login()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ];
                header("Location: /dashboard");
                exit;
            } else {
                $error = "Ongeldige inloggegevens.";
                LoginView::Render($error);
            }
        } else {
            LoginView::Render();
        }
    }

    public static function register()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            if (empty($name) || empty($email) || empty($password) || !in_array($role, ['student', 'teacher'])) {
                $error = "Vul alle velden correct in.";
                RegisterView::Render($error);
                return;
            }

            if (User::findByEmail($email)) {
                $error = "E-mailadres is al in gebruik.";
                RegisterView::Render($error);
                return;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $userId = User::create($name, $email, $hashed, $role);

            if ($role === 'student') {
                Student::create($userId, 'S' . rand(100000, 999999));
            } else {
                Teacher::create($userId, 'Algemeen');
            }

            $_SESSION['user'] = [
                'id' => $userId,
                'name' => $name,
                'role' => $role
            ];

            header("Location: /dashboard");
            exit;
        } else {
            RegisterView::Render();
        }
    }

    public static function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }
}
