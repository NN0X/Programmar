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
                parent::__construct();
                $this->userRepository = new UserRepository();
                $this->courseRepository = new CourseRepository();
        }

        private function getUserData() {
                $userId = $_SESSION['user']['id'];
                return $this->userRepository->getUserById($userId);
        }

        private function calculateProgress(&$courses) {
                foreach ($courses as &$course) {
                        if ($course['total_lessons'] > 0) {
                                $course['progress'] = round(($course['completed_lessons'] / $course['total_lessons']) * 100);
                        } else {
                                $course['progress'] = 0;
                        }
                }
        }

        public function index()
        {
                $user = $this->getUserData();
                $courses = $this->courseRepository->getAllCourses($user['id']);
                $this->calculateProgress($courses);

                $this->render('courses_catalog', [
                        'username' => $user['name'] ? $user['name'] : 'Coder',
                        'ram' => $user['ram'],
                        'courses' => $courses
                ]);
        }

        public function myCourses()
        {
                $user = $this->getUserData();
                $courses = $this->courseRepository->getCoursesByUserId($user['id']);
                $this->calculateProgress($courses);

                $this->render('my_courses', [
                        'username' => $user['name'] ? $user['name'] : 'Coder',
                        'ram' => $user['ram'],
                        'courses' => $courses
                ]);
        }
}
