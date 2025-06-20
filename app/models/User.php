<?php
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
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
}
