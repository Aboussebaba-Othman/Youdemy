<?php
namespace App\Classes;

class TextContent extends CourseContent {
    public function render(): string {
        return '<div class="prose max-w-none">
                    ' . nl2br(htmlspecialchars($this->content)) . '
                </div>';
    }
}