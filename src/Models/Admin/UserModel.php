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
        try {
            $this->connection->beginTransaction();
    
            // 1. Vérifier si l'utilisateur est un étudiant
            $stmt = $this->connection->prepare("SELECT id FROM students WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $studentId = $stmt->fetchColumn();
    
            if ($studentId) {
                // Supprimer les inscriptions de l'étudiant
                $stmt = $this->connection->prepare("DELETE FROM enrollment WHERE student_id = :student_id");
                $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
                $stmt->execute();
    
                // Supprimer l'enregistrement étudiant
                $stmt = $this->connection->prepare("DELETE FROM students WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
    
            // 2. Vérifier si l'utilisateur est un enseignant
            $stmt = $this->connection->prepare("SELECT id FROM teachers WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $teacherId = $stmt->fetchColumn();
    
            if ($teacherId) {
                // Supprimer les inscriptions aux cours de l'enseignant
                $stmt = $this->connection->prepare("
                    DELETE FROM enrollment 
                    WHERE course_id IN (
                        SELECT id FROM courses WHERE teacher_id = :teacher_id
                    )
                ");
                $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
                $stmt->execute();
    
                // Supprimer les cours de l'enseignant
                $stmt = $this->connection->prepare("DELETE FROM courses WHERE teacher_id = :teacher_id");
                $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
                $stmt->execute();
    
                // Supprimer l'enregistrement enseignant
                $stmt = $this->connection->prepare("DELETE FROM teachers WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
    
            // 3. Finalement, supprimer l'utilisateur
            $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            $this->connection->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("Error deleting user: " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
        }
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
    public function deleteTeacherKeepCourses($userId) {
    try {
        $this->connection->beginTransaction();

        $stmt = $this->connection->prepare("SELECT id FROM teachers WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $teacherId = $stmt->fetchColumn();

        if ($teacherId) {
            $stmt = $this->connection->prepare("
                UPDATE courses 
                SET teacher_id = NULL 
                WHERE teacher_id = :teacher_id
            ");
            $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM teachers WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }

        $this->connection->commit();
        return true;

    } catch (PDOException $e) {
        $this->connection->rollBack();
        error_log("Error deleting teacher: " . $e->getMessage());
        throw new Exception("Erreur lors de la suppression de l'enseignant: " . $e->getMessage());
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