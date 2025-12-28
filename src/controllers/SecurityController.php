<?php

require_once "repository/UserRepository.php";
require_once "AppController.php";

class SecurityController extends AppController
{
        private $userRepository;

        public function __construct()
        {
                $this->userRepository = new UserRepository();
        }

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

                $user = $this->userRepository->getUser($email);

                if (!$user)
                {
                        return $this->sendJson(['success' => false, 'message' => 'Invalid credentials'], 401);
                }

                if (!password_verify($password, $user['password']))
                {
                        return $this->sendJson(['success' => false, 'message' => 'Invalid credentials'], 401);
                }

                session_regenerate_id(true);
                $_SESSION['user']['id'] = $user['id'];
                $_SESSION['user']['email'] = $user['email'];

                return $this->sendJson(['success' => true, 'message' => 'Login successful'], 200);
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

                if ($this->userRepository->getUser($email))
                {
                        // TODO: should send email to ask for password reset on same email
                        return $this->sendJson(['success' => false, 'message' => 'Invalid credentials'], 400);
                }

                $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

                $this->userRepository->addUser($email, $hashedPassword);

                return $this->sendJson(['success' => true, 'message' => 'User registered successfully'], 201);
        }

        private function sendJson($data, $statusCode = 200)
        {
                header('Content-Type: application/json');
                http_response_code($statusCode);
                echo json_encode($data);
        }

        private function isPost(): bool
        {
                return $_SERVER['REQUEST_METHOD'] === 'POST';
        }
}
