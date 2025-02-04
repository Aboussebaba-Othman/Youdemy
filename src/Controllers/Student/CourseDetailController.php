<?php
namespace App\Controllers\Student;
use App\Models\Student\CourseDetailModel;
class CourseDetailController {
    private $detailCourseModel;
    public function __construct() {
        $this->detailCourseModel = new CourseDetailModel();
    }
    public function getCourseDetails($courseId) {
        $courseDetails = $this->detailCourseModel->getCourseById($courseId);
        $courseContent = $this->detailCourseModel->getCourseContent($courseId);
        $teacherDetails = $this->detailCourseModel->getTeacherByCourseId($courseId);
        $courseSkills = $this->detailCourseModel->getCourseSkills($courseId);
        
        $isLoggedIn = $this->isLoggedIn();
        $isEnrolled = false;
        $enrolledCourses = [];
        
        if ($isLoggedIn) {
            $userId = $this->getUserId();
            
            // Utilisons le MyCoursesModel pour obtenir tous les cours
            $myCoursesModel = new \App\Models\Student\MyCoursesModel();
            $enrolledCourses = $myCoursesModel->getEnrolledCourses($userId);
            
            $isEnrolled = $this->detailCourseModel->isUserEnrolled($courseId, $userId);
        }
        
        return [
            'course' => $courseDetails,
            'teacher' => $teacherDetails,
            'skills' => $courseSkills,
            'content' => $courseContent,
            'isLoggedIn' => $isLoggedIn,
            'isEnrolled' => $isEnrolled,
            'enrolledCourses' => $enrolledCourses
        ];
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
   
            $userId = $this->getUserId();
            return $this->detailCourseModel->enrollStudent($courseId, $userId);
        
    }
}