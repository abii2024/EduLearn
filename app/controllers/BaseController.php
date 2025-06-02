<?php

class BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header("Location: /login");
            exit;
        }
    }

    protected function requireRole(string $role)
    {
        if (!$this->isLoggedIn() || $_SESSION['user']['role'] !== $role) {
            header("Location: /");
            exit;
        }
    }
}
