<?php
require_once __DIR__ . '/BaseModel.php';

class SalesModel extends BaseModel
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
}
