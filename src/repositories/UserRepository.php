<?php

require_once "Database.php";

class UserRepository
{
        private $database;

        public function __construct()
        {
                $this->database = new Database();
        }

        public function getUser(string $email)
        {
                $stmt = $this->database->connect()->prepare('
                                SELECT * FROM users WHERE email = :email
                        ');
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();

                return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getUserById(int $id)
        {
                $stmt = $this->database->connect()->prepare('
                        SELECT * FROM users WHERE id = :id
                ');
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function addUser(string $email, string $password)
        {
                $stmt = $this->database->connect()->prepare('
                                INSERT INTO users (email, password)
                                VALUES (?, ?)
                        ');

                $stmt->execute([
                        $email,
                        $password
                ]);
        }

        public function updateName(int $userId, string $name)
        {
                $stmt = $this->database->connect()->prepare('
                        UPDATE users SET name = :name WHERE id = :id
                ');
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();
        }

        public function resetAccount(int $userId)
        {
                $db = $this->database->connect();

                $stmt = $db->prepare('
                        DELETE FROM user_courses WHERE user_id = :id
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();
        }

        public function deleteUser(int $userId)
        {
                $stmt = $this->database->connect()->prepare('
                        DELETE FROM users WHERE id = :id
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();
        }
}
