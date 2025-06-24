<?php
class BaseController
{
    protected $pdo;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ Databaseverbinding beschikbaar maken
        require_once dirname(__DIR__) . '/../config/dbConnect.php';
        global $pdo;
        $this->pdo = $pdo;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header("Location: /EduLearn/public/login");
            exit;
        }
    }

    protected function requireRole(string $role)
    {
        if (!$this->isLoggedIn() || $_SESSION['user']['role'] !== $role) {
            header("Location: /EduLearn/public/");
            exit;
        }
    }
}
