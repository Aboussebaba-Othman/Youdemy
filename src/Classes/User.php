<?php

namespace App\Classes;


class User {
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $status;
    protected $role;
    
    public function __construct($id,$name, $email,$password,$status, $role,) {
            $this->id = $id;
            $this->name = $name;
            $this->email = $email;
            $this->password = $password;
            $this->status = $status;
            $this->role = $role;
        }
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getStatus() { return $this->status; }
    public function getRole() { return $this->role; }
    
}