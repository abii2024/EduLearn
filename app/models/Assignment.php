<?php
// app/models/Assignment.php
require_once __DIR__ . '/BaseModel.php';

class Assignment extends BaseModel
{
    protected static $table = 'assignments';
    
    protected $lesson_id;
    protected $title;
    protected $instructions;
    protected $deadline;

    public function __construct($lesson_id = '', $title = '', $instructions = '', $deadline = null)
    {
        $this->lesson_id = $lesson_id;
        $this->title = $title;
        $this->instructions = $instructions;
        $this->deadline = $deadline;
    }

    public function save()
    {
        global $pdo;
        if (isset($this->id)) {
            $stmt = $pdo->prepare("UPDATE assignments SET lesson_id = ?, title = ?, instructions = ?, deadline = ? WHERE id = ?");
            return $stmt->execute([$this->lesson_id, $this->title, $this->instructions, $this->deadline, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO assignments (lesson_id, title, instructions, deadline) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$this->lesson_id, $this->title, $this->instructions, $this->deadline]);
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public static function getAllForStudent($studentId)
    {
        global $pdo;
        // For now, return all assignments (in real app, you'd join with enrollments)
        $stmt = $pdo->query("SELECT * FROM assignments WHERE deadline >= NOW() ORDER BY deadline ASC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getRecentByTeacher($teacherId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT a.*, l.title as lesson_title FROM assignments a 
            JOIN lessons l ON a.lesson_id = l.id 
            WHERE l.teacher_id = ? 
            ORDER BY l.created_at DESC 
            LIMIT 5
        ");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function initializeDatabase()
    {
        global $pdo;
        
        $sql = "CREATE TABLE IF NOT EXISTS assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lesson_id INT,
            title VARCHAR(255) NOT NULL,
            instructions TEXT,
            deadline DATETIME,
            INDEX(lesson_id)
        )";
        
        $pdo->exec($sql);
    }
}
?>
