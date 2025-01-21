<?php
namespace App\Controllers\Student;

use App\Models\Student\MyCoursesModel;

class MyCoursesController {
    private $enrolledCoursesModel;

    public function __construct() {
        $this->enrolledCoursesModel = new MyCoursesModel();
    }

    public function getEnrolledCourses($userId) {
        try {
            return $this->enrolledCoursesModel->getEnrolledCourses($userId);
        } catch (\Exception $e) {
            error_log("Error in getEnrolledCourses: " . $e->getMessage());
            throw $e;
        }
    }
}