<?php
session_start();
require_once "../../../vendor/autoload.php";

use App\Models\Student\CourseDetailModel;

try {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Veuillez vous connecter pour vous inscrire aux cours.";
        header("Location: courseDetail.php?id=" . $_POST['course_id']);
        exit();
    }

    $courseId = $_POST['course_id'] ?? null;
    if (!$courseId) {
        $_SESSION['error'] = "ID du cours non fourni.";
        header("Location: index.php");
        exit();
    }

    $model = new CourseDetailModel();
    
    if ($model->isUserEnrolled($courseId, $_SESSION['user_id'])) {
        $_SESSION['error'] = "Vous êtes déjà inscrit à ce cours.";
        header("Location: courseDetail.php?id=" . $courseId);
        exit();
    }

    $model->enrollStudent($courseId, $_SESSION['user_id']);
    
    $_SESSION['success'] = "Inscription réussie !";
    header("Location: courseDetail.php?id=" . $courseId);
    exit();

} catch (Exception $e) {
    error_log("Error in enrollment: " . $e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
    header("Location: courseDetail.php?id=" . $courseId);
    exit();
}