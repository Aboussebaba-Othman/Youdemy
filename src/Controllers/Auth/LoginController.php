<?php
namespace App\Controllers\Auth;

use App\Models\Auth\LoginModel;

class LoginController {
    private $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return [
                'status' => 'error',
                'message' => 'Email and password are required.'
            ];
        }
        

        $user = $this->loginModel->findUserByEmail($email);
        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ];
        }

         if (!password_verify($password, $user->getHashedPassword())) {
            return [
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ];
        }

        $status = $user->getStatus();
        $role = $user->getRole();
        
        $roleName = '';
        try {
            $roleName = strtolower($role->getTitle());
        } catch (\Exception $e) {
            try {
                $roleName = strtolower($role->getTitle());
            } catch (\Exception $e) {
                error_log("Cannot retrieve role name: " . $e->getMessage());
                $roleName = 'unknown';
            }
        }


        switch ($status) {
            case 'active':
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_role'] = $roleName;
                $_SESSION['user_name'] = $user->getName();

                switch ($roleName) {
                    case 'admin':
                        $redirect = '../admin/home.php';
                        break;
                    case 'teacher':
                        $redirect = '../teacher/home.php';
                        break;
                    case 'student':
                        $redirect = '../student/courseCatalog.php';
                        break;
                    default:
                        return [
                            'status' => 'error',
                            'message' => 'Invalid user role. Role: ' . $roleName
                        ];
                }

                return [
                    'status' => 'success',
                    'message' => 'Login successful',
                    'redirect' => $redirect
                ];

            case 'pending':
                return [
                    'status' => 'error',
                    'message' => 'Your account is pending approval.'
                ];

            case 'suspended':
                return [
                    'status' => 'error',
                    'message' => 'Your account has been suspended. Please contact the administrator.'
                ];

            default:
                return [
                    'status' => 'error',
                    'message' => 'Your account status is not recognized. Please contact support.'
                ];
        }
    }
}