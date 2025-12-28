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
}
