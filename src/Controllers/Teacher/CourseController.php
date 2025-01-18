<?php
namespace App\Controllers\Teacher;

class CourseController {
    private $courseModel;

    public function __construct() {
        $this->courseModel = new \App\Models\Teacher\CourseModel();
    }

    public function handleAddCourse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $courseData = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'category_id' => $_POST['category_id'],
                    'image' => $_POST['coverImageUrl'],
                    'tags' => $_POST['tags'] ?? [],
                    'content' => ''
                ];

                if ($_POST['contentType'] === 'video') {
                    $courseData['content'] = $_POST['videoUrl'];
                } else {
                    $courseData['content'] = $_POST['documentContent'];
                }

                $result = $this->courseModel->addCourse($courseData);
                
                if ($result) {
                    $_SESSION['success'] = "Course added successfully";
                } else {
                    $_SESSION['error'] = "Failed to add course";
                }

            } catch (\Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
    
}