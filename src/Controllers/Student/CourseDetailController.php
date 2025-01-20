<?php
namespace App\Controllers\Student;

use App\Models\Student\CourseDetailModel;

class CourseDetailController {
    private $detailCourseModel;

    public function __construct() {
        $this->detailCourseModel = new CourseDetailModel();
    }

    public function getCourseDetails($courseId) {
        try {
            $courseDetails = $this->detailCourseModel->getCourseById($courseId);
            if (!$courseDetails) {
                throw new \Exception("Course not found");
            }

            $courseSkills = $this->detailCourseModel->getCourseSkills($courseId);

            $teacherDetails = $this->detailCourseModel->getTeacherByCourseId($courseId);

            $isLoggedIn = $this->isLoggedIn();

            $isEnrolled = false;
            if ($isLoggedIn) {
                $userId = $this->getUserId();
                $isEnrolled = $this->detailCourseModel->isUserEnrolled($courseId, $userId);
            }

            return [
                'course' => $courseDetails,
                'teacher' => $teacherDetails,
                'skills' => $courseSkills,
                'isLoggedIn' => $isLoggedIn,
                'isEnrolled' => $isEnrolled
            ];
        } catch (\Exception $e) {
            error_log("Error in getCourseDetails: " . $e->getMessage());
            throw $e;
        }
    }
    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    public function enrollInCourse($courseId) {
        try {
            if (!$this->isLoggedIn()) {
                throw new \Exception("User not logged in");
            }
    
            $userId = $this->getUserId();
            return $this->detailCourseModel->enrollStudent($courseId, $userId);
        } catch (\Exception $e) {
            error_log("Error in enrollInCourse: " . $e->getMessage());
            throw $e;
        }
    }
}