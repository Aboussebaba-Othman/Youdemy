<?php
namespace App\Controllers\Auth;
use App\Models\Auth\RegisterModel;

class RegisterController {
    public function register($postData) {
        if (empty($postData['name']) || empty($postData['email']) || 
            empty($postData['password']) || empty($postData['role_id'])) {
            echo "All fields are required.";
            return;
        }

        if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format.";
            return;
        }

        $name = htmlspecialchars($postData['name']);
        $email = htmlspecialchars($postData['email']);
        $password = password_hash($postData['password'], PASSWORD_BCRYPT);
        $roleId = $postData['role_id']; 
        $level = isset($postData['education_level']) ? 
                 htmlspecialchars($postData['education_level']) : null;
        $specialty = isset($postData['specialty']) ? 
                    htmlspecialchars($postData['specialty']) : null;

        if ($roleId === 3 && empty($level)) {
            echo "Education level is required for students.";
            return;
        }
        if ($roleId === 2 && empty($specialty)) {
            echo "Specialty is required for teachers.";
            return;
        }

        $registerModel = new RegisterModel();
        $result = $registerModel->createUser($name, $email, $password, $roleId, $level, $specialty);

        if ($result === true) {
            header('Location: login.php');
            exit(); 
        } else {
            echo "Registration failed: " . $result;
        }
    }
}