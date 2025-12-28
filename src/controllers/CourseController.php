<?php

require_once "AppController.php";
require_once "repositories/UserRepository.php";
require_once "repositories/CourseRepository.php";

class CourseController extends AppController
{
        private $userRepository;
        private $courseRepository;

        public function __construct()
        {
                $this->userRepository = new UserRepository();
                $this->courseRepository = new CourseRepository();
        }

        public function index()
        {
                $userId = $_SESSION['user']['id'];

                $user = $this->userRepository->getUserById($userId);
                $username = $user['name'] ? $user['name'] : 'Coder';
                $ram = $user['ram'];

                $courses = $this->courseRepository->getAllCourses($userId);

                foreach ($courses as &$course) {
                        if ($course['total_lessons'] > 0) {
                                $course['progress'] = round(($course['completed_lessons'] / $course['total_lessons']) * 100);
                        } else {
                                $course['progress'] = 0;
                        }
                }

                $this->render('courses', [
                        'username' => $username,
                        'ram' => $ram,
                        'courses' => $courses
                ]);
        }
}
