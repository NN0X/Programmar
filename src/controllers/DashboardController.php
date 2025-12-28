<?php

require_once "AppController.php";
require_once "repositories/UserRepository.php";
require_once "repositories/CourseRepository.php";

class DashboardController extends AppController
{
        private $userRepository;
        private $courseRepository;

        public function __construct()
        {
                parent::__construct();
                $this->userRepository = new UserRepository();
                $this->courseRepository = new CourseRepository();
        }

        public function index()
        {
                $userId = $_SESSION['user']['id'];
                $user = $this->userRepository->getUserById($userId);

                $lastCourse = $this->courseRepository->getLastAccessedCourse($userId);

                if ($lastCourse && $lastCourse['total_lessons'] > 0) {
                        $lastCourse['progress'] = round(($lastCourse['completed_lessons'] / $lastCourse['total_lessons']) * 100);
                }

                $this->render('dashboard', [
                        'username' => $user['name'] ? $user['name'] : 'Coder',
                        'ram' => $user['ram'],
                        'course' => $lastCourse
                ]);
        }

        public function settings()
        {
                $this->render('settings');
        }
}
