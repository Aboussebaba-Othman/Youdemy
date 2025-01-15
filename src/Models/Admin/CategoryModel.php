<?php
namespace App\Models\Admin;
use App\Config\DatabaseConnection;
use PDO;
use PDOException;
use Exception;

class CategoryModel {
    private $connection;

    public function __construct() {
        try {
            $db = new DatabaseConnection();
            $this->connection = $db->connect();
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAllCategories() {
        try {
            $query = "SELECT c.*, COUNT(co.id) as course_count 
                     FROM categories c 
                     LEFT JOIN courses co ON c.id = co.category_id 
                     GROUP BY c.id";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve categories: " . $e->getMessage());
        }
    }

    
}