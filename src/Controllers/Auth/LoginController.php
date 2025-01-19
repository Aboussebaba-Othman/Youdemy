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
            error_log("Login attempt with empty email or password");
            return [
                'status' => 'error',
                'message' => 'Email and password are required.'
            ];
        }

        $user = $this->loginModel->findUserByEmail($email);

        error_log("Login attempt for email: " . $email);

        if (!$user) {
            error_log("User not found: " . $email);
            return [
                'status' => 'error',
                'message' => 'Invalid credentials.'
            ];
        }

        

        $status = $user->getStatus();
        error_log("User status: " . $status);

        $role = $user->getRole();
        error_log("User role object: " . print_r($role, true));
        
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

        error_log("Processed role name: " . $roleName);

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
                        $redirect = '../index.php';
                        break;
                    default:
                        error_log("Unexpected role for redirection: " . $roleName);
                        return [
                            'status' => 'error',
                            'message' => 'Invalid user role. Role: ' . $roleName
                        ];
                }

                error_log("Preparing to redirect to: " . $redirect);

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
                error_log("Unhandled user status: " . $status);
                return [
                    'status' => 'error',
                    'message' => 'Your account status is not recognized. Please contact support.'
                ];
        }
    }
}