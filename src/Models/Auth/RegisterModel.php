<?php
namespace App\Models\Auth;
use App\Config\DatabaseConnection;
use PDO;
use Exception;

class RegisterModel {
    private $connexion;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->connexion = $db->connect();
    }

    public function createUser($name, $email, $password, $roleId, $level = null, $specialty = null) {
        try {
            $this->connexion->beginTransaction();

            $checkEmail = "SELECT id FROM users WHERE email = :email";
            $stmt = $this->connexion->prepare($checkEmail);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                throw new Exception('Email already exists.');
            }

            $status = ($roleId == 2) ? "pending" : "active";  

            $userQuery = "INSERT INTO users (name, email, password, status, role_id)
                         VALUES (:name, :email, :password, :status, :role_id)";
            $stmt = $this->connexion->prepare($userQuery);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':role_id', $roleId);
            $stmt->execute();

            $userId = $this->connexion->lastInsertId();

            if ($roleId == 3) { 
                $studentQuery = "INSERT INTO students (user_id, education_level) 
                               VALUES (:user_id, :level)";
                $stmt = $this->connexion->prepare($studentQuery);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':level', $level);
                $stmt->execute();
            } 
            elseif ($roleId == 2) { 
                $teacherQuery = "INSERT INTO teachers (user_id, specialty) 
                               VALUES (:user_id, :specialty)";
                $stmt = $this->connexion->prepare($teacherQuery);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':specialty', $specialty);
                $stmt->execute();
            }

            $this->connexion->commit();
            return true;

        } catch (Exception $e) {
            $this->connexion->rollBack();
            return $e->getMessage();
        }
    }
}