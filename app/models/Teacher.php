<?php
require_once __DIR__ . '/User.php';

class Teacher extends User
{
    public function __construct($name = '', $email = '', $password = '')
    {
        parent::__construct($name, $email, $password, 'teacher');
    }

    // Eventuele teacher-specifieke functies kun je hier toevoegen
}
