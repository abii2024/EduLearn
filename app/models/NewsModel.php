<?php
require_once __DIR__ . '/BaseModel.php';

class NewsModel extends BaseModel
{
    protected static $table = 'news';
    protected $title;
    protected $content;
    protected $created_at;

    public function __construct($title = '', $content = '', $created_at = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s');
    }

    public function save()
    {
        global $pdo;
        if (isset($this->id)) {
            $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
            return $stmt->execute([$this->title, $this->content, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO news (title, content, created_at) VALUES (?, ?, ?)");
            $result = $stmt->execute([$this->title, $this->content, $this->created_at]);
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public static function getLatestNewsStory()
    {
        global $pdo;
        
        try {
            // Use the actual column names from the existing database
            $stmt = $pdo->query("SELECT id, title, body as content, date as created_at FROM news ORDER BY created_at DESC LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If no data exists, create a sample news story
            if (!$result || empty($result)) {
                // Create with actual database structure
                $stmt = $pdo->prepare("INSERT INTO news (title, body, date) VALUES (?, ?, ?)");
                $stmt->execute([
                    'Welkom bij EduLearn!',
                    'We verwelkomen alle nieuwe studenten en docenten op ons platform. Ontdek alle functies en begin vandaag nog met leren!',
                    date('Y-m-d H:i:s')
                ]);
                
                // Return the sample news data with mapped field names
                return [
                    'id' => $pdo->lastInsertId(),
                    'title' => 'Welkom bij EduLearn!',
                    'content' => 'We verwelkomen alle nieuwe studenten en docenten op ons platform. Ontdek alle functies en begin vandaag nog met leren!',
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            // If error occurs, return default data
            return [
                'id' => 1,
                'title' => 'Welkom bij EduLearn!',
                'content' => 'We verwelkomen alle nieuwe studenten en docenten op ons platform. Ontdek alle functies en begin vandaag nog met leren!',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
    }

    public static function initializeDatabase()
    {
        global $pdo;
        
        // Create table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        
        // Check if created_at column exists, if not add it
        try {
            $pdo->query("SELECT created_at FROM news LIMIT 1");
        } catch (PDOException $e) {
            // Column doesn't exist, add it
            $pdo->exec("ALTER TABLE news ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        }
    }
}
