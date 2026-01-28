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
                $this->updatePassiveRam($id);

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
                                INSERT INTO users (email, password, ram, last_ram_check)
                                VALUES (?, ?, 5, CURRENT_TIMESTAMP)
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

        public function deductRam(int $userId)
        {
                $stmt = $this->database->connect()->prepare('
                        UPDATE users 
                        SET ram = GREATEST(0, ram - 1) 
                        WHERE id = :id
                        RETURNING ram
                ');
                $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                $stmt->execute();

                return $stmt->fetchColumn();
        }

        private function updatePassiveRam(int $userId)
        {
                $pdo = $this->database->connect();

                $stmt = $pdo->prepare('SELECT last_ram_check, ram FROM users WHERE id = :id');
                $stmt->execute([':id' => $userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !$user['last_ram_check']) return;

                $lastCheckDate = new DateTime($user['last_ram_check']);
                $now = new DateTime();
                $diff = $now->diff($lastCheckDate);
                $hoursPassed = $diff->h + ($diff->days * 24);

                if ($hoursPassed >= 1 && $user['ram'] < 5)
                {
                        $hoursToAdd = min(5 - $user['ram'], $hoursPassed);
                        $stmt = $pdo->prepare('
                                UPDATE users
                                SET ram = ram + :hours,
                                        last_ram_check = last_ram_check + interval \':hours hours\' -- Add specific hours instead of resetting to "now"
                                WHERE id = :id
                        ');
                        $stmt->execute([':hours' => $hoursToAdd, ':id' => $userId]);
                }
        }
}
