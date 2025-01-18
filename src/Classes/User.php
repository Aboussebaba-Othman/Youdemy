<?php

namespace App\Classes;

class User {
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $status;
    protected $role;
    
    public function __construct($id, $name, $email, $role, $password, $status) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
        
        $this->status = strtolower(trim($status ?? 'pending'));
    }
    
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    
    public function getHashedPassword() { return $this->password; }
    
    public function getStatus() { 
        $validStatuses = ['active', 'suspended', 'pending'];
        return in_array($this->status, $validStatuses) ? $this->status : 'pending';
    }
    
    public function getRole() { return $this->role; }
}