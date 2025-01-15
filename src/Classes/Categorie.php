<?php

namespace App\Classes;


class Categorie {
    public $id;
    public $title;
    
    public function __construct($id, $title) {
            $this->id = $id;
            $this->title = $title;
         
    }


    public function getId() { return $this->id; }
    public function getName() { return $this->title; }
    
}