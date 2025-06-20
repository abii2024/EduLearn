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
        $stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
