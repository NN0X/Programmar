<?php

require_once "AppController.php";

class SecurityController extends AppController
{
        public function login()
        {
                if (!$this->isPost())
                {
                        return $this->render("login");
                }

                $content = trim(file_get_contents("php://input"));
                $data = json_decode($content, true);

                $email = $data['email'] ?? '';
                $password = $data['password'] ?? '';

                $user = $this->getUser($email, $password);

                if ($user)
                {
                        session_regenerate_id(true);
                        $_SESSION['user']['id'] = $user;
                        return $this->sendJson(['success' => true, 'message' => 'Login successful'], 200);
                }
                else
                {
                        return $this->sendJson(['success' => false, 'message' => 'Invalid credentials'], 401);
                }

        }

        public function register()
        {
                if (!$this->isPost())
                {
                        return $this->render("register");
                }

                $content = trim(file_get_contents("php://input"));
                $data = json_decode($content, true);

                $email = $data['email'] ?? '';
                $password = $data['password'] ?? '';
                $confirmedPassword = $data['confirmedPassword'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                        return $this->sendJson(['success' => false, 'message' => 'Invalid email format'], 400);
                }

                if (strlen($password) < 6)
                {
                        return $this->sendJson(['success' => false, 'message' => 'Password must be at least 6 characters long'], 400);
                }

                if ($password !== $confirmedPassword)
                {
                        return $this->sendJson(['success' => false, 'message' => 'Passwords do not match'], 400);
                }

                $existingUser = $this->getUser($email, $password);
                if ($existingUser)
                {
                        // TODO: should send email to ask for password reset on same email
                        return $this->sendJson(['success' => false, 'message' => 'Invalid credentials'], 400);
                }

                $this->saveUser($email, $password);

                return $this->sendJson(['success' => true, 'message' => 'User registered successfully'], 201);
        }

        private function sendJson($data, $statusCode = 200)
        {
                header('Content-Type: application/json');
                http_response_code($statusCode);
                echo json_encode($data);
        }

        private function saveUser($email, $password)
        {
                // TODO: Save user to database
                return true;
        }

        private function getUser(string $email, string $password): int|bool
        {
                $hashedPassword = $password; // TODO: hash and salt

                // TODO: Replace with real database lookup
                if ($email === 'nox@nox.pl' && $hashedPassword === 'admin')
                {
                        return rand(1, 1000);
                }
                else
                {
                        return false;
                }
        }

        private function isPost(): bool
        {
                return $_SERVER['REQUEST_METHOD'] === 'POST';
        }
}

?>
