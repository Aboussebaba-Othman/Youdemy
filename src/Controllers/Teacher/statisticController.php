<?php
namespace App\Controllers\Teacher;

use App\Models\Teacher\StatisticModel;
use App\Models\Teacher\CourseModel; 
use Exception;

class StatisticController
{
    private $statisticModel;
    private $courseModel; 

    public function __construct()
    {
        $this->statisticModel = new StatisticModel();
        $this->courseModel = new CourseModel(); 
    }

    public function getStatistics()
    {
        try {
            $teacherId = $this->getTeacherId();
            
            error_log("Teacher ID retrieved: " . $teacherId);

            if ($teacherId === 0) {
                return [
                    'error' => 'Utilisateur non authentifié ou non un professeur',
                    'status' => false
                ];
            }

            $totalCourses = $this->statisticModel->getTotalCourses($teacherId);
            $totalStudents = $this->statisticModel->getTotalStudents($teacherId);
            $top3Courses = $this->statisticModel->getTop3Courses($teacherId);
            $top3Students = $this->statisticModel->getTop3Students($teacherId);
            return [
                'status' => true,
                'totalCourses' => $totalCourses,
                'totalStudents' => $totalStudents,
                'top3Courses' => $top3Courses,
                'top3Students' => $top3Students
            ];
        } catch (Exception $e) {
            error_log("Erreur de statistiques : " . $e->getMessage());
            error_log("Trace de l'erreur : " . $e->getTraceAsString());
            
            return [
                'error' => 'Impossible de charger les statistiques : ' . $e->getMessage(),
                'status' => false
            ];
        }
    }

    private function getTeacherId(): int
    {
        if (!isset($_SESSION['user_id'])) {
            error_log("Aucun user_id dans la session");
            return 0;
        }

        $userId = (int)$_SESSION['user_id'];
        $teacher = $this->courseModel->getTeacherByUserId($userId);

        if ($teacher === null) {
            error_log("Aucun professeur trouvé pour l'utilisateur : " . $userId);
            return 0;
        }

        return $teacher->getId();
    }
}