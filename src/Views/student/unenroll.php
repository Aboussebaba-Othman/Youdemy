<?php
session_start();
require_once "../../../vendor/autoload.php";
use App\Models\Student\MyCoursesModel;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $courseId = $_POST['course_id'];
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }

    try {
        $model = new MyCoursesModel();
        $userId = $_SESSION['user_id'];

        if ($model->unenrollCourse($courseId, $userId)) {
            $_SESSION['success'] = "Votre inscription au cours a été annulée avec succès.";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'annulation de l'inscription.";
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Une erreur est survenue : " . $e->getMessage();
    }
}

header('Location: myCourses.php');
exit;