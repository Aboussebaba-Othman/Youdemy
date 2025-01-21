<?php
namespace App\Classes;

abstract class CourseContent {
    protected $content;
    
    public function __construct($content) {
        $this->content = $content;
    }

    abstract public function render(): string;
}


 