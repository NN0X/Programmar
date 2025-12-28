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
                        SELECT c.id, c.title, c.description, c.icon, c.total_lessons, uc.completed_lessons
                        FROM courses c
                        JOIN user_courses uc ON c.id = uc.course_id
                        WHERE uc.user_id = :id
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getAllCourses(int $userId): array
        {
                $stmt = $this->database->connect()->prepare('
                        SELECT c.id, c.title, c.description, c.icon, c.total_lessons, 
                               COALESCE(uc.completed_lessons, 0) as completed_lessons
                        FROM courses c
                        LEFT JOIN user_courses uc ON c.id = uc.course_id AND uc.user_id = :id
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
