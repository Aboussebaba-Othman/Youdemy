<?php
namespace App\Classes;

class Student extends User{
    private $id;
    private $level;
    private $user;

    public function __construct($id, $level, $user) {
        $this->id = $id;
        $this->level = $level;
        $this->user = $user;
    }

    public function getId() {
        return $this->id;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getUser() {
        return $this->user;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}
