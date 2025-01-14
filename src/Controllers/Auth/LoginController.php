<?php
namespace App\Controllers\Auth;

use App\Classes\User;
use App\Config\DatabaseConnection;
use App\Models\Auth\LoginModel;
use PDO;

class LoginController{

   
    public function login($email, $password){
        $userModel = new LoginModel();
        $user =  $userModel->findUserByEmail($email, $password);
        if($user == null)
        {
            echo "user not found please check ...";
        }
        else{
            if($user->getRole() == "Admin")
            {
                header("Location:../admin/home.php");
            }
            else if($user->getRole() == "Teacher")
            {
              header("Location:../teacher/home.php");
            }
            else if($user->getRole() == "Student")
            {
              header("Location:../index.php");
            }
        }
    }
}