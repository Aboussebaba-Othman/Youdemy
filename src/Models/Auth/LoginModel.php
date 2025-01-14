<?php
namespace App\Models\Auth;

use App\Classes\Role;
use App\Classes\User;
use App\Config\DatabaseConnection;
use PDO;

class LoginModel{
    private $connexion; 

    public function __construct(){
            $db = new DatabaseConnection();
            $this->connexion = $db->connect();
    }

    public function findUserByEmail($email, $password){

        $query = "SELECT users.id,users.name, users.email, users.password,users.status, roles.id as role_id, roles.name as `role`
        FROM users 
        JOIN roles ON roles.id = users.role_id 
        WHERE users.email = :email";
        $stmt = $this->connexion->prepare($query); 
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         if(!$row){
         return null;
         }
         else{
            $role = new Role($row["role_id"], $row["role"]);
            return new User($row['id'], $row["name"],$row["email"],$role,$row["password"],$row["role"],$row["status"]);
            // return new User($row['id'], $row["name"],$row["email"], $row["password"], $row["role"]);
//        }
         }
    }
}