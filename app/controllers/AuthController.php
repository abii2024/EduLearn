<?php
require_once dirname(__DIR__, 2) . '/config/dbConnect.php';

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';

// Views
require_once __DIR__ . '/../views/login.php';
require_once __DIR__ . '/../views/register.php';

class AuthController
{
    public static function showLogin()
    {
        // Session is already started in routing_entry.php
        LoginView::Render();
    }

    public static function processLogin()
    {
        // Session is already started in routing_entry.php
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
                header("Location: /EduLearn/public/dashboard");
                exit;
            } else {
                $error = "Ongeldige inloggegevens.";
                LoginView::Render($error);
            }
        }
    }

    public static function showRegister()
    {
        // Session is already started in routing_entry.php
        RegisterView::Render();
    }

    public static function processRegister()
    {
        // Session is already started in routing_entry.php
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
            
            // Create user directly
            $user = new User($name, $email, $hashed, $role);
            if ($user->save()) {
                $_SESSION['user'] = [
                    'id' => $user->getID(),
                    'name' => $name,
                    'role' => $role
                ];

                header("Location: /EduLearn/public/dashboard");
                exit;
            } else {
                $error = "Er is een fout opgetreden bij het aanmaken van het account.";
                RegisterView::Render($error);
            }
        }
    }

    public static function logout()
    {
        // Session is already started in routing_entry.php
        session_destroy();
        header("Location: /EduLearn/public/login");
        exit;
    }
}
