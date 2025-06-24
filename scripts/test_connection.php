<?php
// scripts/test_connection.php - Test database connection

$host = 'localhost';
$username = 'root';
$password = '';

echo "Testing MySQL connection...\n";

try {
    // Try to connect to MySQL using XAMPP socket
    $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    $pdo = new PDO("mysql:unix_socket=$socket;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ MySQL connection successful!\n";
    
    // Show existing databases
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Existing databases:\n";
    foreach ($databases as $db) {
        echo "  - $db\n";
    }
    
} catch (PDOException $e) {
    echo "❌ MySQL connection failed: " . $e->getMessage() . "\n";
    echo "\nPossible solutions:\n";
    echo "1. Make sure XAMPP is running (start Apache and MySQL)\n";
    echo "2. Check if MySQL is running on port 3306\n";
    echo "3. Verify MySQL username/password in config/dbConnect.php\n";
}
?>
