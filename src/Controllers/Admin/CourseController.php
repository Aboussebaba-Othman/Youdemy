<?php
namespace App\Controllers\Admin;

use App\Models\Admin\CourseModel;

class CourseController {
    private $courseModel;
    
    public function __construct() {
        $this->courseModel = new CourseModel();
    }

    public function index() {
        try {
            return $this->courseModel->getCourses();
        } catch (\Exception $e) {
            error_log("Error fetching courses: " . $e->getMessage());
            return [];
        }
    }

    public function deleteControllerCourse($id) {
        if ($id > 0) {
            try {
                $this->courseModel->deleteCourse($id);
            } catch (\Exception $e) {
                throw new \Exception("Erreur lors de la suppression du cours : " . $e->getMessage());
            }
        } else {
            throw new \Exception("ID invalide pour la suppression");
        }
    }
}
