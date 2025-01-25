<?php
namespace App\Classes;

class VideoContent extends CourseContent {
    public function render(): string {
        return '<div class="aspect-w-16 aspect-h-9 mb-6">
                    <iframe 
                        src="' . htmlspecialchars($this->content) . '"
                        class="w-full h-[500px] rounded-lg shadow-lg"
                        allowfullscreen>
                    </iframe>
                </div>';
    }
}


