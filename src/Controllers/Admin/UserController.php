<?php
namespace App\Controllers\Admin;
use App\Models\Admin\UserModel;

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function fetchUsers() {
        return $this->userModel->fetchAllUsers();
    }

    public function deleteUser($id) {
        return $this->userModel->deleteUser($id);
    }

    public function toggleStatus($id) {
        return $this->userModel->toggleUserStatus($id);
    }

    public function getPendingTeachers() {
        return $this->userModel->getPendingTeachers();
    }

    public function handleValidation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $action = $_POST['action'] ?? null;
    
            if ($userId && $action) {
                if ($action === 'approve') {
                    $success = $this->userModel->updateUserStatus($userId, 'active');
                    if ($success) {
                        $_SESSION['success'] = "Teacher approved successfully";
                    } else {
                        $_SESSION['error'] = "Failed to approve teacher";
                    }
                } else if ($action === 'reject') {
                    $success = $this->userModel->deleteTecher($userId);
                    if ($success) {
                        $_SESSION['success'] = "Teacher rejected and removed";
                    } else {
                        $_SESSION['error'] = "Failed to reject teacher";
                    }
                }
            }
            
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}