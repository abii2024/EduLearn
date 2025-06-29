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
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Input validation
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Voer een geldig e-mailadres in.";
                LoginView::Render($error);
                return;
            }

            if (empty($password)) {
                $error = "Voer uw wachtwoord in.";
                LoginView::Render($error);
                return;
            }

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
            $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            // Input validation
            if (empty($name) || strlen($name) < 2) {
                $error = "Naam moet ten minste 2 karakters bevatten.";
                RegisterView::Render($error);
                return;
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Voer een geldig e-mailadres in.";
                RegisterView::Render($error);
                return;
            }

            if (empty($password) || strlen($password) < 6) {
                $error = "Wachtwoord moet ten minste 6 karakters bevatten.";
                RegisterView::Render($error);
                return;
            }

            if (!in_array($role, ['student', 'teacher', 'admin'])) {
                $error = "Selecteer een geldige rol.";
                RegisterView::Render($error);
                return;
            }

            if (User::findByEmail($email)) {
                $error = "E-mailadres is al in gebruik.";
                RegisterView::Render($error);
                return;
            }

            $hashed = password_hash($password, PASSWORD_DEFAULT, [
                'cost' => 12 // Higher cost for better security
            ]);
            
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
