<?php
// config/dbConnect.php

$host = 'localhost';
$dbname = 'EduLearn';
$username = 'root';
$password = ''; // pas aan indien nodig

try {
    // For XAMPP on macOS, specify the socket path
    $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    $dsn = "mysql:unix_socket=$socket;dbname=$dbname;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
