<?php
// app/models/ClassModel.php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/interfaces/ORMinterface.php';

class ClassModel extends BaseModel implements ORMinterface  
{
    protected static $table = 'classes';
    
    protected $name;
    protected $description;
    protected $teacher_id;
    protected $created_at;

    public function __construct($name = '', $description = '', $teacher_id = null, $created_at = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->teacher_id = $teacher_id;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }

    public function save()
    {
        global $pdo;
        if (isset($this->id)) {
            $stmt = $pdo->prepare("UPDATE classes SET name = ?, description = ?, teacher_id = ? WHERE id = ?");
            return $stmt->execute([$this->name, $this->description, $this->teacher_id, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO classes (name, description, teacher_id, created_at) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$this->name, $this->description, $this->teacher_id, $this->created_at]);
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public static function getByTeacher($teacherId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM classes WHERE teacher_id = ? ORDER BY created_at DESC");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getStudentsInClass($classId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.email, ce.enrolled_at 
            FROM users u 
            JOIN class_enrollments ce ON u.id = ce.student_id 
            WHERE ce.class_id = ? AND u.role = 'student'
            ORDER BY u.name ASC
        ");
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAvailableStudents($classId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT u.id, u.name, u.email 
            FROM users u 
            WHERE u.role = 'student' 
            AND u.id NOT IN (
                SELECT ce.student_id 
                FROM class_enrollments ce 
                WHERE ce.class_id = ?
            )
            ORDER BY u.name ASC
        ");
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function enrollStudent($classId, $studentId)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT IGNORE INTO class_enrollments (class_id, student_id) VALUES (?, ?)");
        return $stmt->execute([$classId, $studentId]);
    }

    public static function unenrollStudent($classId, $studentId)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM class_enrollments WHERE class_id = ? AND student_id = ?");
        return $stmt->execute([$classId, $studentId]);
    }

    public static function getClassesForStudent($studentId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT c.*, u.name as teacher_name 
            FROM classes c 
            JOIN class_enrollments ce ON c.id = ce.class_id 
            JOIN users u ON c.teacher_id = u.id
            WHERE ce.student_id = ?
            ORDER BY c.name ASC
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteById($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function deleteClassWithEnrollments($id)
    {
        global $pdo;
        
        try {
            $pdo->beginTransaction();
            
            // First delete all enrollments for this class
            $stmt = $pdo->prepare("DELETE FROM class_enrollments WHERE class_id = ?");
            $stmt->execute([$id]);
            
            // Update lessons to remove class association
            $stmt = $pdo->prepare("UPDATE lessons SET class_id = NULL WHERE class_id = ?");
            $stmt->execute([$id]);
            
            // Then delete the class
            $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            $pdo->commit();
            return $result;
            
        } catch (Exception $e) {
            $pdo->rollback();
            throw $e;
        }
    }

    // ORM interface methods
    public static function findByID($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM classes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getTeacherId() { return $this->teacher_id; }
    public function getCreatedAt() { return $this->created_at; }
}
?>
