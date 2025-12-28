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
                        SELECT c.id, c.title, c.description, c.icon, 
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
                        SELECT c.id, c.title, c.description, c.icon, c.created_at,
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
                        SELECT c.id, c.title, c.description, c.icon, uc.completed_lessons
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

        public function setActiveCourse(int $userId, int $courseId)
        {
                $pdo = $this->database->connect();

                $stmt = $pdo->prepare('
                        SELECT 1 FROM user_courses WHERE user_id = :uid AND course_id = :cid
                ');
                $stmt->execute([':uid' => $userId, ':cid' => $courseId]);

                if ($stmt->fetch()) {
                        $update = $pdo->prepare('
                                UPDATE user_courses 
                                SET last_accessed = CURRENT_TIMESTAMP 
                                WHERE user_id = :uid AND course_id = :cid
                        ');
                        $update->execute([':uid' => $userId, ':cid' => $courseId]);
                } else {
                        $insert = $pdo->prepare('
                                INSERT INTO user_courses (user_id, course_id, last_accessed)
                                VALUES (:uid, :cid, CURRENT_TIMESTAMP)
                        ');
                        $insert->execute([':uid' => $userId, ':cid' => $courseId]);
                }
        }
}
