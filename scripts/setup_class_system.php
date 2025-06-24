<?php
// Extended database setup for class management system
require_once __DIR__ . '/../config/dbConnect.php';

echo "Setting up class management system...\n";

try {
    // Create classes table
    echo "Creating classes table...\n";
    $sql_classes = "CREATE TABLE IF NOT EXISTS classes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        teacher_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX(teacher_id)
    )";
    $pdo->exec($sql_classes);
    
    // Create class_enrollments table (many-to-many relationship between classes and students)
    echo "Creating class_enrollments table...\n";
    $sql_enrollments = "CREATE TABLE IF NOT EXISTS class_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        class_id INT NOT NULL,
        student_id INT NOT NULL,
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_enrollment (class_id, student_id),
        INDEX(class_id),
        INDEX(student_id)
    )";
    $pdo->exec($sql_enrollments);
    
    // Add class_id to lessons table to associate lessons with classes
    echo "Adding class_id column to lessons table...\n";
    $sql_add_class_id = "ALTER TABLE lessons ADD COLUMN class_id INT NULL AFTER teacher_id";
    try {
        $pdo->exec($sql_add_class_id);
        echo "Added class_id column to lessons table.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "class_id column already exists in lessons table.\n";
        } else {
            throw $e;
        }
    }
    
    // Add foreign key constraint for class_id in lessons table
    echo "Adding foreign key constraint for lessons.class_id...\n";
    try {
        $sql_fk = "ALTER TABLE lessons ADD CONSTRAINT fk_lessons_class_id 
                   FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL";
        $pdo->exec($sql_fk);
        echo "Added foreign key constraint for lessons.class_id.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false || 
            strpos($e->getMessage(), 'already exists') !== false) {
            echo "Foreign key constraint already exists for lessons.class_id.\n";
        } else {
            echo "Note: Could not add foreign key constraint: " . $e->getMessage() . "\n";
        }
    }
    
    // Insert sample data if tables are empty
    $check_classes = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    if ($check_classes == 0) {
        echo "Adding sample classes...\n";
        
        // Get teacher IDs
        $teachers = $pdo->query("SELECT id, name FROM users WHERE role='teacher'")->fetchAll();
        
        if (count($teachers) > 0) {
            $stmt = $pdo->prepare("INSERT INTO classes (name, description, teacher_id) VALUES (?, ?, ?)");
            
            // Create sample classes
            $sampleClasses = [
                ['Webontwikkeling Basis', 'Leer de basis van HTML, CSS en JavaScript', $teachers[0]['id']],
                ['PHP & MySQL', 'Serverside programmeren met PHP en databases', $teachers[0]['id']]
            ];
            
            foreach ($sampleClasses as $class) {
                $stmt->execute($class);
                echo "Created class: {$class[0]} for teacher: {$teachers[0]['name']}\n";
            }
            
            // Get student IDs and enroll them in classes
            $students = $pdo->query("SELECT id, name FROM users WHERE role='student'")->fetchAll();
            if (count($students) > 0) {
                echo "Enrolling students in classes...\n";
                $stmt_enroll = $pdo->prepare("INSERT INTO class_enrollments (class_id, student_id) VALUES (?, ?)");
                
                $classes = $pdo->query("SELECT id, name FROM classes")->fetchAll();
                foreach ($classes as $class) {
                    foreach ($students as $student) {
                        $stmt_enroll->execute([$class['id'], $student['id']]);
                        echo "Enrolled {$student['name']} in {$class['name']}\n";
                    }
                }
            }
            
            // Update existing lessons to be associated with first class
            if (count($sampleClasses) > 0) {
                $first_class_id = $pdo->lastInsertId() - 1; // Get first class ID
                $pdo->exec("UPDATE lessons SET class_id = $first_class_id WHERE class_id IS NULL");
                echo "Associated existing lessons with first class.\n";
            }
        }
    }
    
    echo "✅ Class management system setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error setting up class management system: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
