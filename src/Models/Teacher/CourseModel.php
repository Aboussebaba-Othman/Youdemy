<?php
namespace App\Models\Teacher;

use PDO;
use PDOException;

class CourseModel {
    private $connection;

    public function __construct() {
        $db = new \App\Config\DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function addCourse($data) {
        try {
            // Start transaction
            $this->connection->beginTransaction();

            // Insert course
            $sql = "INSERT INTO Courses (title, description, content, image, category_id, teacher_id) 
                    VALUES (:title, :description, :content, :image, :category_id, :teacher_id)";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'teacher_id' => 21 // For now, hardcode to 1
            ]);

            $courseId = $this->connection->lastInsertId();

            // Insert tags if any
            if (!empty($data['tags'])) {
                $tagSql = "INSERT INTO Coursetag (course_id, tag_id) VALUES (:course_id, :tag_id)";
                $tagStmt = $this->connection->prepare($tagSql);

                foreach ($data['tags'] as $tagId) {
                    $tagStmt->execute([
                        'course_id' => $courseId,
                        'tag_id' => $tagId
                    ]);
                }
            }

            // Commit transaction
            $this->connection->commit();
            return true;

        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new \Exception("Failed to add course: " . $e->getMessage());
        }
    }
    
}