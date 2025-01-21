<?php
namespace App\Models\Admin;
use App\Config\DatabaseConnection;
use PDO;
use PDOException;
use Exception;

class TagModel
{
    private $connection;

    public function __construct() {
        try {
            $db = new DatabaseConnection();
            $this->connection = $db->connect();
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAllTags() {
        try {
            $query = "SELECT * FROM tags ORDER BY id ASC"; 
            $stmt = $this->connection->prepare($query); 
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des tags : " . $e->getMessage());
        }
    }

    public function addTags($tags)
    {
        $tagsArray = array_map('trim', explode(',', $tags));
        foreach ($tagsArray as $tag) {
            if (!empty($tag)) {
                $stmt = $this->connection->prepare("INSERT INTO tags (title) VALUES (:tag)"); 
                $stmt->bindParam(':tag', $tag);
                $stmt->execute();
            }
        }
    }

    public function deleteTag($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM tags WHERE id = :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>
