<?php

require_once "Database.php";

class CourseRepository
{
        private $database;

        public function __construct()
        {
                $this->database = new Database();
        }

        public function getCoursesByUserId(int $userId): array
        {
                $stmt = $this->database->connect()->prepare('
                        SELECT c.id, c.title, c.description, c.icon, c.total_lessons, 
                               uc.completed_lessons, uc.last_accessed
                        FROM courses c
                        JOIN user_courses uc ON c.id = uc.course_id
                        WHERE uc.user_id = :id
                        ORDER BY uc.last_accessed DESC
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllCourses(int $userId): array
        {
                $stmt = $this->database->connect()->prepare('
                        SELECT c.id, c.title, c.description, c.icon, c.total_lessons, c.created_at,
                               COALESCE(uc.completed_lessons, 0) as completed_lessons
                        FROM courses c
                        LEFT JOIN user_courses uc ON c.id = uc.course_id AND uc.user_id = :id
                        ORDER BY c.title ASC
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getLastAccessedCourse(int $userId)
        {
                $stmt = $this->database->connect()->prepare('
                        SELECT c.title, c.description, c.icon, c.total_lessons, uc.completed_lessons
                        FROM courses c
                        JOIN user_courses uc ON c.id = uc.course_id
                        WHERE uc.user_id = :id
                        ORDER BY uc.last_accessed DESC
                        LIMIT 1
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetch(PDO::FETCH_ASSOC);
        }
}
