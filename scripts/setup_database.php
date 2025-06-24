<?php
// scripts/setup_database.php - Complete database setup script

// Database configuration
$host = 'localhost';
$dbname = 'EduLearn';
$username = 'root';
$password = ''; // adjust if needed

try {
    // First, connect without specifying database to check if it exists
    $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    $pdo_check = new PDO("mysql:unix_socket=$socket;charset=utf8mb4", $username, $password);
    $pdo_check->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo_check->query("SHOW DATABASES LIKE '$dbname'");
    $database_exists = $stmt->rowCount() > 0;
    
    if (!$database_exists) {
        echo "Creating database '$dbname'...\n";
        $pdo_check->exec("CREATE DATABASE $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database '$dbname' created successfully!\n";
    } else {
        echo "Database '$dbname' already exists.\n";
    }
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:unix_socket=$socket;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database '$dbname'.\n";
    
    // Create users table
    echo "Creating users table...\n";
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('student', 'teacher') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_users);
    
    // Create news table
    echo "Creating news table...\n";
    $sql_news = "CREATE TABLE IF NOT EXISTS news (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_news);
    
    // Create sales table
    echo "Creating sales table...\n";
    $sql_sales = "CREATE TABLE IF NOT EXISTS sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        product_name VARCHAR(255),
        amount DECIMAL(10,2),
        sale_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_sales);
    
    // Create lessons table (for future use)
    echo "Creating lessons table...\n";
    $sql_lessons = "CREATE TABLE IF NOT EXISTS lessons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        teacher_id INT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql_lessons);
    
    // Create assignments table (for future use)
    echo "Creating assignments table...\n";
    $sql_assignments = "CREATE TABLE IF NOT EXISTS assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lesson_id INT,
        title VARCHAR(255) NOT NULL,
        instructions TEXT,
        deadline DATETIME,
        FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql_assignments);
    
    // Check if we need to add sample data
    $check_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $check_news = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
    $check_sales = $pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();
    
    // Add sample data if tables are empty
    if ($check_users == 0) {
        echo "Adding sample users...\n";
        
        // Add sample teacher
        $teacher_password = password_hash('teacher123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Jan de Vries', 'teacher@edulearn.nl', $teacher_password, 'teacher']);
        $teacher_id = $pdo->lastInsertId();
        
        // Add sample student
        $student_password = password_hash('student123', PASSWORD_DEFAULT);
        $stmt->execute(['Emma Jansen', 'student@edulearn.nl', $student_password, 'student']);
        $student_id = $pdo->lastInsertId();
        
        echo "Sample users created:\n";
        echo "- Teacher: teacher@edulearn.nl (password: teacher123)\n";
        echo "- Student: student@edulearn.nl (password: student123)\n";
    }
    
    if ($check_news == 0) {
        echo "Adding sample news...\n";
        $stmt = $pdo->prepare("INSERT INTO news (title, content) VALUES (?, ?)");
        $stmt->execute([
            'Welkom bij EduLearn!',
            'We verwelkomen alle nieuwe studenten en docenten op ons platform. Ontdek alle functies en begin vandaag nog met leren! EduLearn biedt een moderne en interactieve leeromgeving waar studenten en docenten kunnen samenwerken.'
        ]);
        
        $stmt->execute([
            'Nieuwe cursussen beschikbaar',
            'We hebben nieuwe cursussen toegevoegd aan ons platform! Van programmeren tot grafisch ontwerp, er is voor iedereen wel iets interessants. Schrijf je vandaag nog in!'
        ]);
    }
    
    if ($check_sales == 0) {
        echo "Adding sample sales...\n";
        $stmt = $pdo->prepare("INSERT INTO sales (student_id, product_name, amount, sale_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([1, 'Premium Programmeren Cursus', 89.99, date('Y-m-d')]);
        $stmt->execute([1, 'Web Development Bootcamp', 149.99, date('Y-m-d', strtotime('-1 day'))]);
        $stmt->execute([1, 'Database Design Cursus', 69.99, date('Y-m-d', strtotime('-2 days'))]);
    }
    
    // Add sample lessons if we have users
    if ($check_users > 0) {
        $check_lessons = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
        if ($check_lessons == 0) {
            echo "Adding sample lessons...\n";
            $stmt = $pdo->prepare("INSERT INTO lessons (teacher_id, title, description) VALUES (?, ?, ?)");
            
            // Get teacher ID
            $teacher = $pdo->query("SELECT id FROM users WHERE role = 'teacher' LIMIT 1")->fetch();
            if ($teacher) {
                $stmt->execute([
                    $teacher['id'],
                    'Inleiding tot PHP',
                    'Leer de basis van PHP programmeren. In deze cursus behandelen we variabelen, loops, functies en object-georiënteerd programmeren.'
                ]);
                
                $stmt->execute([
                    $teacher['id'],
                    'Database Design',
                    'Ontdek hoe je efficiënte databases ontwerpt. We behandelen normalisatie, relaties en SQL queries.'
                ]);
                
                $stmt->execute([
                    $teacher['id'],
                    'Web Development Fundamentals',
                    'De basis van webontwikkeling: HTML, CSS, JavaScript en moderne frameworks.'
                ]);
            }
        }
        
        // Add sample assignments
        $check_assignments = $pdo->query("SELECT COUNT(*) FROM assignments")->fetchColumn();
        if ($check_assignments == 0) {
            echo "Adding sample assignments...\n";
            $stmt = $pdo->prepare("INSERT INTO assignments (lesson_id, title, instructions, deadline) VALUES (?, ?, ?, ?)");
            
            // Get lesson IDs
            $lessons = $pdo->query("SELECT id FROM lessons LIMIT 3")->fetchAll();
            if (count($lessons) > 0) {
                $stmt->execute([
                    $lessons[0]['id'],
                    'PHP Basis Oefeningen',
                    'Maak de oefeningen in hoofdstuk 1-3 van het cursusmateriaal.',
                    date('Y-m-d H:i:s', strtotime('+1 week'))
                ]);
                
                if (count($lessons) > 1) {
                    $stmt->execute([
                        $lessons[1]['id'],
                        'Database Ontwerp Project',
                        'Ontwerp een database voor een bibliotheeksysteem.',
                        date('Y-m-d H:i:s', strtotime('+2 weeks'))
                    ]);
                }
                
                if (count($lessons) > 2) {
                    $stmt->execute([
                        $lessons[2]['id'],
                        'Responsive Website',
                        'Bouw een responsive website met HTML, CSS en JavaScript.',
                        date('Y-m-d H:i:s', strtotime('+3 weeks'))
                    ]);
                }
            }
        }
    }
    
    echo "\n✅ Database setup completed successfully!\n";
    echo "You can now access your EduLearn application at: http://localhost/EduLearn/public/\n";
    echo "\nSample login credentials:\n";
    echo "Teacher: teacher@edulearn.nl / teacher123\n";
    echo "Student: student@edulearn.nl / student123\n";
    
} catch (PDOException $e) {
    echo "❌ Database setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
