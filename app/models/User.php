<?php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/interfaces/ORMinterface.php';

class User extends BaseModel implements ORMinterface
{
    protected static $table = 'users';

    protected $name;
    protected $email;
    protected $password; // hashed
    protected $role;

    public function __construct($name = '', $email = '', $password = '', $role = 'student')
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function save()
    {
        global $pdo;

        if (isset($this->id)) {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
            return $stmt->execute([$this->name, $this->email, $this->password, $this->role, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$this->name, $this->email, $this->password, $this->role]);
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public static function findByEmail($email)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    // ORM interface methods
    public static function findByID($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function initializeDatabase()
    {
        global $pdo;
        
        // Create table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('student', 'teacher', 'admin') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        
        // Check if created_at column exists, if not add it
        try {
            $pdo->query("SELECT created_at FROM users LIMIT 1");
        } catch (PDOException $e) {
            // Column doesn't exist, add it
            $pdo->exec("ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        }
    }
}
