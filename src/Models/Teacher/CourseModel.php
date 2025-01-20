<?php
namespace App\Models\Teacher;

use App\Config\DatabaseConnection;
use App\Classes\Teacher;
use App\Classes\User;
use PDO;
use PDOException;
use Exception;

class CourseModel
{
    private $connection;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function addCourse($data, $teacherId)
    {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO courses (title, description, content, image, category_id, teacher_id) 
                    VALUES (:title, :description, :content, :image, :category_id, :teacher_id)";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'teacher_id' => $teacherId
            ]);

            error_log("addCourse SQL query: " . $stmt->queryString);
            error_log("addCourse SQL params: " . print_r([
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'teacher_id' => $teacherId
            ], true));

            $courseId = $this->connection->lastInsertId();

            if (!empty($data['tags'])) {
                $tagSql = "INSERT INTO coursetag (course_id, tag_id) VALUES (:course_id, :tag_id)";
                $tagStmt = $this->connection->prepare($tagSql);

                foreach ($data['tags'] as $tagId) {
                    $tagStmt->execute([
                        'course_id' => $courseId,
                        'tag_id' => $tagId
                    ]);
                }
            }

            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("Error in addCourse: " . $e->getMessage());
            throw new Exception("Failed to add course: " . $e->getMessage());
        } catch (Exception $e) {
            $this->connection->rollBack();
            error_log("Error in addCourse: " . $e->getMessage());
            throw new Exception("Failed to add course: " . $e->getMessage());
        }
    }

    public function getTeacherByUserId($userId): ?Teacher
{
    try {
        $stmt = $this->connection->prepare("
            SELECT t.id, t.specialty, u.id as user_id, u.name, u.email
            FROM Teachers t
            JOIN Users u ON t.user_id = u.id
            WHERE u.id = ? AND u.role_id = (SELECT id FROM Roles WHERE name = 'teacher')
        ");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Teacher(
                $row['id'],
                $row['specialty'],
                new User(
                    $row['user_id'],
                    $row['name'],
                    $row['email'],
                    null,
                    null,
                    null
                )
            );
        }
    } catch (PDOException $e) {
        error_log("Error getting teacher: " . $e->getMessage());
    }
    return null;
}

    public function getTeacherCourses($teacherId)
    {
        try {
            $sql = "SELECT 
                    c.id,
                    c.title,
                    c.description,
                    c.image,
                    c.content,
                    cat.title as category_name,
                    COUNT(DISTINCT e.student_id) as student_count
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN enrollment e ON c.id = e.course_id
                WHERE c.teacher_id = :teacher_id
                GROUP BY c.id
                ORDER BY c.id DESC";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching courses: " . $e->getMessage());
            throw new Exception("Error fetching courses: " . $e->getMessage());
        }
    }

    public function deleteCourse($courseId, $teacherId)
    {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id FROM courses WHERE id = :id AND teacher_id = :teacher_id"
            );
            $stmt->execute(['id' => $courseId, 'teacher_id' => $teacherId]);
            
            if (!$stmt->fetch()) {
                throw new Exception("Course not found or unauthorized");
            }

            $this->connection->beginTransaction();

            $stmt = $this->connection->prepare("DELETE FROM coursetag WHERE course_id = :course_id");
            $stmt->execute(['course_id' => $courseId]);

            $stmt = $this->connection->prepare("DELETE FROM enrollment WHERE course_id = :course_id");
            $stmt->execute(['course_id' => $courseId]);

            $stmt = $this->connection->prepare("DELETE FROM courses WHERE id = :id AND teacher_id = :teacher_id");
            $stmt->execute(['id' => $courseId, 'teacher_id' => $teacherId]);

            $this->connection->commit();
            return true;

        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("Error deleting course: " . $e->getMessage());
            throw new Exception("Error deleting course: " . $e->getMessage());
        }
    }

    public function getCourse($courseId, $teacherId)
    {
        try {
            $sql = "SELECT c.*, cat.title as category_name,
                    GROUP_CONCAT(t.id) as tag_ids
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    LEFT JOIN coursetag ct ON c.id = ct.course_id
                    LEFT JOIN tags t ON ct.tag_id = t.id
                    WHERE c.id = :id AND c.teacher_id = :teacher_id
                    GROUP BY c.id";
    
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'id' => $courseId,
                'teacher_id' => $teacherId
            ]);
            
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($course) {
                $course['tags'] = $course['tag_ids'] ? explode(',', $course['tag_ids']) : [];
                unset($course['tag_ids']);
            }
            
            return $course;
    
        } catch (PDOException $e) {
            error_log("Error fetching course: " . $e->getMessage());
            throw new Exception("Error fetching course: " . $e->getMessage());
        }
    }
    
    public function updateCourse($courseId, $data, $teacherId)
    {
        try {
            $this->connection->beginTransaction();
    
            $stmt = $this->connection->prepare(
                "SELECT id FROM courses WHERE id = :id AND teacher_id = :teacher_id"
            );
            $stmt->execute([
                'id' => $courseId,
                'teacher_id' => $teacherId
            ]);
            
            if (!$stmt->fetch()) {
                throw new Exception("Course not found or unauthorized");
            }
    
            $sql = "UPDATE courses SET 
                    title = :title,
                    description = :description,
                    content = :content,
                    image = :image,
                    category_id = :category_id
                    WHERE id = :id AND teacher_id = :teacher_id";
    
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'id' => $courseId,
                'teacher_id' => $teacherId
            ]);
    
            if (isset($data['tags'])) {
                $stmt = $this->connection->prepare("DELETE FROM coursetag WHERE course_id = ?");
                $stmt->execute([$courseId]);
    
                if (!empty($data['tags'])) {
                    $tagSql = "INSERT INTO coursetag (course_id, tag_id) VALUES (?, ?)";
                    $tagStmt = $this->connection->prepare($tagSql);
                    
                    foreach ($data['tags'] as $tagId) {
                        $tagStmt->execute([$courseId, $tagId]);
                    }
                }
            }
    
            $this->connection->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("Error updating course: " . $e->getMessage());
            throw new Exception("Error updating course: " . $e->getMessage());
        }
    }
public function getCourseNames($teacherId = null)
{
    try {
        $sql = "SELECT id, title FROM Courses WHERE teacher_id = :teacher_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (\PDOException $e) {
        error_log("Error getting course names: " . $e->getMessage());
        return [];
    }
}
}