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
                error_log("Course with ID $id deleted successfully.");
            } catch (\Exception $e) {
                error_log("Error deleting course with ID $id: " . $e->getMessage());
                throw new \Exception("Error deleting course: " . $e->getMessage());
            }
        } else {
            throw new \Exception("Invalid ID provided for deletion.");
        }
    }



    public function getDashboardData() {
        $data = [];

        $data['total_courses'] = $this->courseModel->getTotalCourses();
        $data['total_users'] = $this->courseModel->getTotalUsers();
        $data['total_teachers'] = $this->courseModel->getTotalTeachers();
        $data['total_students'] = $this->courseModel->getTotalStudents();

        $data['top_teachers'] = $this->courseModel->getTopTeachers();

        return $data;
    }
    
}
