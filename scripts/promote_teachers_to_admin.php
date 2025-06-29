<?php
// Script om alle docenten te promoveren tot admin
require_once __DIR__ . '/../config/dbConnect.php';

try {
    // Update database schema to allow admin role if not already done
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'teacher', 'admin') NOT NULL");
    echo "✅ Database schema updated to support admin role.\n";
    
    // Promote all teachers to admin
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE role = 'teacher'");
    $result = $stmt->execute();
    
    if ($result) {
        $updatedCount = $stmt->rowCount();
        echo "✅ Successfully promoted {$updatedCount} teachers to admin role.\n";
    } else {
        echo "❌ Failed to promote teachers to admin.\n";
    }
    
    // Show current admin users
    $stmt = $pdo->query("SELECT name, email, role FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n📋 Current administrators:\n";
    foreach ($admins as $admin) {
        echo "- {$admin['name']} ({$admin['email']})\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
