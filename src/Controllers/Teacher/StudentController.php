<?php
namespace App\Controllers\Teacher;

use App\Models\Teacher\StudentModel;
use App\Models\Teacher\CourseModel;

class StudentController
{
    private $studentModel;
    private $courseModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->courseModel = new CourseModel();
    }

    public function getStudentsList()
    {
        $teacher = $this->getTeacher();
        if ($teacher === null) {
            return [
                'students' => [],
                'courses' => [],
                'teacherName' => 'Inconnu',
                'teacherInitial' => 'T'
            ];
        }

        $teacherId = $teacher->getId();
        $students = $this->studentModel->getAllStudents($teacherId);
        $courses = $this->courseModel->getCourseNames($teacherId);
        
        $teacherName = $teacher->getUser()->getName();
        $teacherInitial = strtoupper(substr($teacherName, 0, 1));

        return [
            'students' => $students,
            'courses' => $courses,
            'teacherName' => $teacherName,
            'teacherInitial' => $teacherInitial
        ];
    }

    // private function getTeacher()
    // {
    //     if (isset($_SESSION['user_id'])) {
    //         return $this->courseModel->getTeacherByUserId($_SESSION['user_id']);
            
    //     }
    //     return null;
    // }
}