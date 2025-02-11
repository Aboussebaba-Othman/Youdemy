<?php
namespace App\Models\Auth;

use App\Classes\Role;
use App\Classes\User;
use App\Config\DatabaseConnection;
use PDO;

class LoginModel {
    private $connexion;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->connexion = $db->connect();
    }

    public function findUserByEmail($email) {
        $query = "SELECT 
                    users.id, 
                    users.name, 
                    users.email, 
                    users.password, 
                    users.status,
                    roles.id as role_id, 
                    roles.name as role_name
                  FROM users
                  JOIN roles ON roles.id = users.role_id
                  WHERE users.email = :email";

        try {

            $stmt = $this->connexion->prepare($query);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) {
                error_log("No user found with email: " . $email);
                return null;
            }

            $role = new Role(
                $row['role_id'], 
                $row['role_name']
            );

            return new User(
                $row['id'],
                $row['name'],
                $row['email'],
                $role,
                $row['password'],
                $row['status']
            );

        } catch (\PDOException $e) {
            error_log("Database error in findUserByEmail: " . $e->getMessage());
            return null;
        }
    }
}