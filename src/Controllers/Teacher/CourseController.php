<?php
namespace App\Controllers\Teacher;
use App\Models\Teacher\CourseModel;
use App\Models\Teacher\CategoryModel;
use App\Models\Teacher\TagModel;
class CourseController {
    private $courseModel;
    private $categoryModel;
    private $tagModel;
    
    public function __construct() {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }
    
    public function handleAddCourse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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
    
                if (!empty($_POST['course_id'])) {
                    $result = $this->courseModel->updateCourse($_POST['course_id'], $courseData, 21);
                    $_SESSION['success'] = "Course updated successfully";
                } else {
                    $result = $this->courseModel->addCourse($courseData);
                    $_SESSION['success'] = "Course added successfully";
                }
    
                if (!$result) {
                    $_SESSION['error'] = "Operation failed";
                }
                
            } catch (\Exception $e) {
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
                'courses' => []
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
    public function getCourse($courseId) {
        try {
            $teacherId = 21; 
            $course = $this->courseModel->getCourse($courseId, $teacherId);
            
            if (!$course) {
                throw new \Exception("Course not found");
            }
            
            return $course;
        } catch (\Exception $e) {
            throw new \Exception("Error getting course: " . $e->getMessage());
        }
    }
    
    public function updateCourse($courseId, $courseData) {
        try {
            $teacherId = 21; 
            
            if (empty($courseData['title']) || empty($courseData['category_id'])) {
                throw new \Exception("Title and category are required");
            }
    
            return $this->courseModel->updateCourse($courseId, $courseData, $teacherId);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
}