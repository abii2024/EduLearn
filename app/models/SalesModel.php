<?php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/interfaces/ORMinterface.php';

class SalesModel extends BaseModel implements ORMinterface
{
    protected static $table = 'sales';

    protected $student_id;
    protected $product_name;
    protected $amount;
    protected $sale_date;

    public function __construct($student_id = '', $product_name = '', $amount = 0, $sale_date = null)
    {
        $this->student_id = $student_id;
        $this->product_name = $product_name;
        $this->amount = $amount;
        $this->sale_date = $sale_date ?? date('Y-m-d');
    }

    public function save()
    {
        global $pdo;

        if (isset($this->id)) {
            $stmt = $pdo->prepare("UPDATE sales SET student_id = ?, product_name = ?, amount = ?, sale_date = ? WHERE id = ?");
            return $stmt->execute([$this->student_id, $this->product_name, $this->amount, $this->sale_date, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO sales (student_id, product_name, amount, sale_date) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$this->student_id, $this->product_name, $this->amount, $this->sale_date]);
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public static function findByStudentID($studentId)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM sales WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function initializeDatabase()
    {
        global $pdo;
        
        // Create table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS sales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT,
            product_name VARCHAR(255),
            amount DECIMAL(10,2),
            sale_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        
        // Check if created_at column exists, if not add it
        try {
            $pdo->query("SELECT created_at FROM sales LIMIT 1");
        } catch (PDOException $e) {
            // Column doesn't exist, add it
            $pdo->exec("ALTER TABLE sales ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        }
    }
    
    public static function getMainSale()
    {
        global $pdo;
        
        // Check if table exists and has data
        try {
            // Use the actual column names from the existing database
            $stmt = $pdo->query("SELECT id, title as product_name, body, price as amount, date as sale_date, created_at FROM sales ORDER BY created_at DESC LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If no data exists, create a sample sale
            if (!$result || empty($result)) {
                // Create with actual database structure
                $stmt = $pdo->prepare("INSERT INTO sales (title, body, price, date) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    'Premium Cursus Programmeren',
                    'Ontdek onze premium cursus en verbeter je vaardigheden!',
                    89.99,
                    date('Y-m-d H:i:s')
                ]);
                
                // Return the sample sale data with mapped field names
                return [
                    'id' => $pdo->lastInsertId(),
                    'product_name' => 'Premium Cursus Programmeren',
                    'amount' => 89.99,
                    'sale_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            // If error occurs, return default data
            return [
                'id' => 1,
                'product_name' => 'Premium Cursus Programmeren',
                'amount' => 89.99,
                'sale_date' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
    }

    // ORM interface methods
    public static function findByID($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM sales");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        global $pdo;
        if (isset($this->id)) {
            $stmt = $pdo->prepare("DELETE FROM sales WHERE id = ?");
            return $stmt->execute([$this->id]);
        }
        return false;
    }

    public function getID()
    {
        return $this->id ?? null;
    }
}
