<?php
namespace App\Controllers\Teacher;

class CourseController {
    private $courseModel;
    private $categoryModel;
    private $tagModel;
    
    public function __construct() {
        $this->courseModel = new \App\Models\Teacher\CourseModel();
        $this->categoryModel = new \App\Models\Teacher\CategoryModel();
        $this->tagModel = new \App\Models\Teacher\TagModel();
    }
    
    public function handleAddCourse() {
        error_log("handleAddCourse called");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("POST data: " . print_r($_POST, true));
                
                if (empty($_POST['title']) || empty($_POST['category_id'])) {
                    $_SESSION['error'] = "Title and category are required";
                    return;
                }
                
                $courseData = [
                    'title' => trim($_POST['title']),
                    'description' => trim($_POST['description']),
                    'category_id' => (int)$_POST['category_id'],
                    'image' => trim($_POST['coverImageUrl']),
                    'tags' => isset($_POST['tags']) ? (array)$_POST['tags'] : [],
                    'content' => ''
                ];
                
                if ($_POST['contentType'] === 'video') {
                    $courseData['content'] = trim($_POST['videoUrl']);
                } else {
                    $courseData['content'] = trim($_POST['documentContent']);
                }
                
                $result = $this->courseModel->addCourse($courseData);
                
                if ($result) {
                    $_SESSION['success'] = "Course added successfully";
                } else {
                    $_SESSION['error'] = "Failed to add course";
                }
                
            } catch (\Exception $e) {
                error_log("Error adding course: " . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
            }
        }
    }
    
    public function getHomeData(): array {
        try {
            $teacherId = 21; 
            return [
                'categories' => $this->categoryModel->getCategories(),
                'tags' => $this->tagModel->getTags(),
                'courses' => $this->courseModel->getTeacherCourses($teacherId)
                
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'categories' => [],
                'tags' => [],
                'courses' => [],
                'stats' => []
            ];
        }
    }
    public function deleteCourse($courseId) {
        try {
            $teacherId = 21; 
            
            if ($this->courseModel->deleteCourse($courseId, $teacherId)) {
                $_SESSION['success'] = "Cours supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du cours";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }
}