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
                     GROUP BY c.id, c.title";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve categories: " . $e->getMessage());
        }
    }

    public function createCategory($title) {
        if (empty($title)) {
            throw new Exception("Category title cannot be empty");
        }

        try {
            $query = "INSERT INTO categories (title) VALUES (:title)";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':title', $title);
            
            if (!$stmt->execute()) {
                throw new Exception("Category creation failed");
            }
            
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Failed to create category: " . $e->getMessage());
        }
    }

    public function updateCategory($id, $title) {
        if (empty($id) || empty($title)) {
            throw new Exception("Category ID and title are required");
        }

        try {
            $query = "UPDATE categories SET title = :title WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Category update failed");
            }
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Failed to update category: " . $e->getMessage());
        }
    }

    public function getCategoryById($id) {
        try {
            $query = "SELECT * FROM categories WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$category) {
                throw new Exception("Category not found");
            }
            
            return $category;
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve category: " . $e->getMessage());
        }
    }

    public function deleteCategory($id) {
        try {
            $this->connection->beginTransaction();
    
            $updateQuery = "UPDATE courses SET category_id = NULL WHERE category_id = :id";
            $updateStmt = $this->connection->prepare($updateQuery);
            $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $updateStmt->execute();
    
            $deleteQuery = "DELETE FROM categories WHERE id = :id";
            $deleteStmt = $this->connection->prepare($deleteQuery);
            $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $deleteStmt->execute();
    
            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new Exception("Database error: " . $e->getMessage());
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
    
}