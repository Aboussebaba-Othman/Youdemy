<?php

namespace App\Controllers\Admin;

use App\Models\Admin\UserModel;

class UserController
{
    private $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function fetchUsers()
    {
        return $this->userModel->fetchAllUsers();
    }

    public function deleteUser($id)
    {
        return $this->userModel->deleteUser($id);
    }

    public function toggleStatus($id)
    {
        return $this->userModel->toggleUserStatus($id);
    }
}
