<?php
require_once __DIR__ . '/interfaces/ORMinterface.php';
require_once dirname(__DIR__) . '/../config/dbConnect.php';

abstract class BaseModel implements ORMinterface
{
    protected static $table; // Wordt overschreven in child-class
    protected $id;

    public function getID()
    {
        return $this->id;
    }

    public function delete()
    {
        global $pdo;
        if ($this->id) {
            $stmt = $pdo->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
            return $stmt->execute([$this->id]);
        }
        return false;
    }

    public static function findByID($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Deze wordt verplicht door elk kind-model overschreven
    abstract public function save();
}
