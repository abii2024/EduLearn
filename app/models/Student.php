<?php
require_once __DIR__ . '/User.php';

class Student extends User
{
    public function __construct($name = '', $email = '', $password = '')
    {
        parent::__construct($name, $email, $password, 'student');
    }

    // Eventuele student-specifieke functies kun je hier toevoegen
}
