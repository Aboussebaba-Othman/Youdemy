<?php
namespace App\Controllers\Auth;
use App\Models\Auth\LoginModel;

class LoginController {
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return [
                'status' => 'error',
                'message' => 'Email and password are required.'
            ];
        }

        $userModel = new LoginModel();
        $user = $userModel->findUserByEmail($email);

        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'User not found. Please check your credentials.'
            ];
        }

        // Add password verification
        // if (!password_verify($password, $user->getPassword())) {
        //     return [
        //         'status' => 'error',
        //         'message' => 'Invalid credentials.'
        //     ];
        // }

        $status = $user->getStatus();

        if ($status === 'pending') {
            return [
                'status' => 'error',
                'message' => 'Your account is pending approval. Please wait for admin verification.'
            ];
        }

        if ($status === 'suspended') {
            return [
                'status' => 'error',
                'message' => 'Your account has been suspended. Please contact the administrator.'
            ];
        }

        if ($status === 'active') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_role'] = $user->getRole()->getName();
            $_SESSION['user_name'] = $user->getName();

            $roleName = $user->getRole()->getName();
            switch ($roleName) {
                case 'Admin':
                    header("Location: ../admin/home.php");
                    break;
                case 'Teacher':
                    header("Location: ../teacher/home.php");
                    break;
                case 'Student':
                    header("Location: ../index.php");
                    break;
                default:
                    return [
                        'status' => 'error',
                        'message' => 'Invalid user role.'
                    ];
            }
            exit();
        }

        return [
            'status' => 'error',
            'message' => 'An unexpected error occurred during login. Please try again later.'
        ];
    }
}