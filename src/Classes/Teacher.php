<?php
namespace App\Classes;

class Teacher {
    private $id;
    private $specialty;
    private $user;

    public function __construct($id, $specialty, $user) {
        $this->id = $id;
        $this->specialty = $specialty;
        $this->user = $user;
    }

    public function getId() {
        return $this->id;
    }

    public function getSpecialty() {
        return $this->specialty;
    }

    public function getUser() {
        return $this->user;
    }

    public function setSpecialty($specialty) {
        $this->specialty = $specialty;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}
