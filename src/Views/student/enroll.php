<?php
session_start();
require_once "../../../vendor/autoload.php";
use App\Controllers\Student\CourseDetailController;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $courseId = $_POST['course_id'];
    
    try {
        $controller = new CourseDetailController();
        
        if (!$controller->isLoggedIn()) {
            header('Location: ../auth/login.php');
            exit;
        }
        
        $controller->enrollInCourse($courseId);
        
        $_SESSION['enrollment_success'] = true;
        $_SESSION['course_title'] = $_POST['course_title']; 
        
        header("Location: myCourses.php");
        exit;
        
    } catch (\Exception $e) {
        $_SESSION['enrollment_error'] = $e->getMessage();
        header("Location: courseDetail.php?id=$courseId");
        exit;
    }
}