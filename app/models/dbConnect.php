<?php

$host = 'localhost';
$dbname = 'EduLearn';
$username = 'root';
$password = ''; // Voeg jouw wachtwoord toe als je die hebt

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
