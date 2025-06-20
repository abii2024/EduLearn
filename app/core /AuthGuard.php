<?php
require_once __DIR__ . '/Session.php';

class AuthGuard
{
    public static function requireLogin()
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole($role)
    {
        Session::start();
        if (Session::getUserRole() !== $role) {
            header('Location: /');
            exit;
        }
    }
}
