<?php
namespace App\Models\Teacher;

use App\Config\DatabaseConnection;
use PDO;
use PDOException;

class CategoryModel
{
    private PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = (new DatabaseConnection())->connect();
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

public function getCategories(): array
{
    try {
        $stmt = $this->connection->prepare("
            SELECT id, title  # Supprimé la virgule après title
            FROM Categories
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching categories: " . $e->getMessage());
        throw new \Exception("Error fetching categories");
    }
}
}