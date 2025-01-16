<?php
namespace App\Models\Admin;
use App\Config\DatabaseConnection;
use PDO;
use PDOException;
use Exception;

class UserModel {
    private $connection;

    public function __construct() {
        try {
            $db = new DatabaseConnection();
            $this->connection = $db->connect();
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function fetchAllUsers() {
        $query = "
            SELECT users.id, users.name, users.email, users.status, roles.name AS role
            FROM users
            JOIN roles ON users.role_id = roles.id
            WHERE users.status != 'pending'
        ";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleUserStatus($id) {
        $stmt = $this->connection->prepare("SELECT status FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $currentStatus = $stmt->fetchColumn();

        $newStatus = ($currentStatus === 'active') ? 'suspended' : 'active';

        $updateStmt = $this->connection->prepare("UPDATE users SET status = :status WHERE id = :id");
        $updateStmt->bindParam(':status', $newStatus);
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $updateStmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getPendingTeachers() {
        try {
            $query = "SELECT u.id, u.name, u.email, t.specialty
                     FROM users u
                     JOIN teachers t ON u.id = t.user_id
                     WHERE u.status = 'pending'
                     ORDER BY u.id DESC";
           
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Pending teachers found: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("Error fetching pending teachers: " . $e->getMessage());
            return [];
        }
    }

    public function updateUserStatus($userId, $newStatus) {
        try {
            $stmt = $this->connection->prepare("
                UPDATE users 
                SET status = :status 
                WHERE id = :id
            ");
            $stmt->bindParam(':status', $newStatus);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating user status: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteTecher($userId) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM teachers WHERE user_id = :id");
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
    
            $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
}