<?php
// app/models/Lesson.php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/interfaces/ORMinterface.php';

class Lesson extends BaseModel implements ORMinterface
{
    protected static $table = 'lessons';
    
    protected $teacher_id;
    protected $title;
    protected $description;
    protected $class_id;
    protected $created_at;

    public function __construct($teacher_id = '', $title = '', $description = '', $class_id = null, $created_at = null)
    {
        $this->teacher_id = $teacher_id;
        $this->title = $title;
        $this->description = $description;
        $this->class_id = $class_id;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }

    public function save()
    {
        global $pdo;
        if (isset($this->id)) {
            $stmt = $pdo->prepare("UPDATE lessons SET teacher_id = ?, title = ?, description = ?, class_id = ? WHERE id = ?");
            return $stmt->execute([$this->teacher_id, $this->title, $this->description, $this->class_id, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO lessons (teacher_id, title, description, class_id, created_at) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$this->teacher_id, $this->title, $this->description, $this->class_id, $this->created_at]);
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public static function getAllForStudent($studentId)
    {
        global $pdo;
        // Get lessons from classes the student is enrolled in
        $stmt = $pdo->prepare("
            SELECT l.*, c.name as class_name, u.name as teacher_name 
            FROM lessons l 
            JOIN classes c ON l.class_id = c.id
            JOIN class_enrollments ce ON c.id = ce.class_id
            JOIN users u ON l.teacher_id = u.id
            WHERE ce.student_id = ?
            ORDER BY l.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllByTeacher($teacherId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT l.*, c.name as class_name 
            FROM lessons l 
            LEFT JOIN classes c ON l.class_id = c.id
            WHERE l.teacher_id = ? 
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getLessonsByClass($classId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT l.*, u.name as teacher_name 
            FROM lessons l 
            JOIN users u ON l.teacher_id = u.id
            WHERE l.class_id = ? 
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ORM interface methods
    public static function findByID($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM lessons");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function initializeDatabase()
    {
        global $pdo;
        
        $sql = "CREATE TABLE IF NOT EXISTS lessons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            teacher_id INT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(teacher_id)
        )";
        
        $pdo->exec($sql);
    }
}
?>
