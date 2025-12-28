<?php

require_once "AppController.php";
require_once "repositories/UserRepository.php";
require_once "repositories/CourseRepository.php";
require_once "services/ContentService.php";

class DashboardController extends AppController
{
        private $userRepository;
        private $courseRepository;
        private $contentService;

        public function __construct()
        {
                parent::__construct();
                $this->userRepository = new UserRepository();
                $this->courseRepository = new CourseRepository();
                $this->contentService = new ContentService();
        }

        public function index()
        {
                $userId = $_SESSION['user']['id'];
                $user = $this->userRepository->getUserById($userId);

                $lastCourse = $this->courseRepository->getLastAccessedCourse($userId);

                if ($lastCourse) {
                        $lastCourse['total_lessons'] = 0;
                        $lastCourse['progress'] = 0;

                        $details = $this->contentService->getCourseDetails($lastCourse['id']);

                        if ($details) {
                                $lastCourse['total_lessons'] = $details['total_lessons'];
                        }

                        if ($lastCourse['total_lessons'] > 0) {
                                $lastCourse['progress'] = round(($lastCourse['completed_lessons'] / $lastCourse['total_lessons']) * 100);
                        }
                }

                $this->render('dashboard', [
                        'username' => $user['name'] ? $user['name'] : 'Coder',
                        'ram' => $user['ram'],
                        'course' => $lastCourse
                ]);
        }

        public function settings()
        {
                $userId = $_SESSION['user']['id'];
                $user = $this->userRepository->getUserById($userId);

                $this->render('settings', [
                        'username' => $user['name']
                ]);
        }

        public function updateProfile()
        {
                if (!$this->isPost()) {
                        header("Location: /settings");
                        return;
                }

                $userId = $_SESSION['user']['id'];
                $name = $_POST['name'];

                $this->userRepository->updateName($userId, $name);
                header("Location: /settings");
        }

        public function resetAccount()
        {
                if (!$this->isPost()) {
                        header("Location: /settings");
                        return;
                }

                $userId = $_SESSION['user']['id'];
                $this->userRepository->resetAccount($userId);

                header("Location: /dashboard");
        }

        public function deleteAccount()
        {
                if (!$this->isPost()) {
                        header("Location: /settings");
                        return;
                }

                $userId = $_SESSION['user']['id'];
                $this->userRepository->deleteUser($userId);

                session_unset();
                session_destroy();

                header("Location: /login");
                exit();
        }

        public function logout()
        {
                session_unset();
                session_destroy();
                header("Location: /login");
                exit();
        }

        private function isPost(): bool
        {
                return $_SERVER['REQUEST_METHOD'] === 'POST';
        }
}
