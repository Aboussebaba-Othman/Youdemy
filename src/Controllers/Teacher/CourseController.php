<?php
namespace App\Controllers\Teacher;

use App\Models\Teacher\CourseModel;
use App\Models\Teacher\CategoryModel;
use App\Models\Teacher\TagModel;
use App\Classes\Teacher;
use Exception;

class CourseController
{
    private $courseModel;
    private $categoryModel;
    private $tagModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }

    public function handleAddCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $courseData = [
                    'title' => trim($_POST['title']),
                    'description' => trim($_POST['description']),
                    'category_id' => (int)$_POST['category_id'],
                    'image' => trim($_POST['coverImageUrl']),
                    'tags' => isset($_POST['tags']) ? (array)$_POST['tags'] : [],
                    'content' => ''
                ];

                if ($_POST['contentType'] === 'video') {
                    $courseData['content'] = trim($_POST['videoUrl']);
                } else {
                    $courseData['content'] = trim($_POST['documentContent']);
                }

                $teacher = $this->getTeacher();
                $teacherId = $teacher ? $teacher->getId() : 0;

                if (!$this->isValidTeacher($teacher)) {
                    $_SESSION['error'] = "Invalid teacher ID: " . $teacherId;
                    return;
                }

                if (!empty($_POST['course_id'])) {
                    $result = $this->courseModel->updateCourse($_POST['course_id'], $courseData, $teacherId);
                    $_SESSION['success'] = "Course updated successfully";
                } else {
                    $result = $this->courseModel->addCourse($courseData, $teacherId);
                    $_SESSION['success'] = "Course added successfully";
                }

                if (!$result) {
                    $_SESSION['error'] = "Operation failed";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                error_log("Error in CourseController::handleAddCourse: " . $e->getMessage());
            }
        }
    }

    private function getTeacher(): ?Teacher
    {
        if (isset($_SESSION['user_id'])) {
            $userId = (int)$_SESSION['user_id'];
            $teacher = $this->courseModel->getTeacherByUserId($userId);
            return $teacher;
        }
        return null;
    }

    private function isValidTeacher(?Teacher $teacher): bool
    {
        return $teacher !== null;
    }

    public function getHomeData(): array
    {
        try {
            $teacherId = $this->getTeacher() ? $this->getTeacher()->getId() : 0;
            return [
                'categories' => $this->categoryModel->getCategories(),
                'tags' => $this->tagModel->getTags(),
                'courses' => $this->courseModel->getTeacherCourses($teacherId)
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'categories' => [],
                'tags' => [],
                'courses' => []
            ];
        }
    }

    public function deleteCourse($courseId)
    {
        try {
            $teacherId = $this->getTeacher() ? $this->getTeacher()->getId() : 0;
            if ($this->courseModel->deleteCourse($courseId, $teacherId)) {
                $_SESSION['success'] = "Cours supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du cours";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }

    public function getCourse($courseId)
    {
        try {
            $teacherId = $this->getTeacher() ? $this->getTeacher()->getId() : 0;
            $course = $this->courseModel->getCourse($courseId, $teacherId);
            if (!$course) {
                throw new \Exception("Course not found");
            }
            return $course;
        } catch (\Exception $e) {
            throw new \Exception("Error getting course: " . $e->getMessage());
        }
    }

    public function updateCourse($courseId, $postData)
    {
        try {
            $teacherId = $this->getTeacher() ? $this->getTeacher()->getId() : 0;

            $currentCourse = $this->courseModel->getCourse($courseId, $teacherId);
            if (!$currentCourse) {
                throw new \Exception("Course not found");
            }

            $courseData = [
                'title' => trim($postData['title']),
                'description' => trim($postData['description']),
                'category_id' => (int)$postData['category_id'],
                'image' => trim($postData['coverImageUrl']),
                'tags' => isset($postData['tags']) ? (array)$postData['tags'] : [],
                'content' => $currentCourse['content'] 
            ];

            if ($postData['contentType'] === 'video' && !empty($postData['videoUrl'])) {
                $courseData['content'] = trim($postData['videoUrl']);
            } elseif ($postData['contentType'] === 'document' && !empty($postData['documentContent'])) {
                $courseData['content'] = trim($postData['documentContent']);
            }

            if (empty($courseData['title']) || empty($courseData['category_id'])) {
                throw new \Exception("Title and category are required");
            }

            if ($this->courseModel->updateCourse($courseId, $courseData, $teacherId)) {
                $_SESSION['success'] = "Course updated successfully";
                return true;
            } else {
                $_SESSION['error'] = "Failed to update course";
                return false;
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }
}