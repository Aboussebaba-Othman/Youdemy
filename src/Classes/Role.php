<?php

namespace App\Classes;

class Role {
    public $id;
    private $title;
    
    public function __construct($id, $title) {
            $this->id = $id;
            $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
}