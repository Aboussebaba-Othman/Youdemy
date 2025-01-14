<?php

namespace App\Classes;


class Tag {
    public $id;
    public $titre;
    
    public function __construct($id, $titre) {
            $this->id = $id;
            $this->titre = $titre;
         
    }


    public function getId() { return $this->id; }
    public function getName() { return $this->titre; }
    
}