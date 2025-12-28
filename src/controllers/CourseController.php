<?php

require_once "AppController.php";
require_once "repositories/UserRepository.php";
require_once "repositories/CourseRepository.php";
require_once "services/ContentService.php";

class CourseController extends AppController
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

        private function getUserData()
        {
                $userId = $_SESSION['user']['id'];
                return $this->userRepository->getUserById($userId);
        }

        private function hydrateWithServiceData(&$courses)
        {
                foreach ($courses as &$course)
                {
                        $course['total_lessons'] = 0;
                        $course['progress'] = 0;

                        $details = $this->contentService->getCourseDetails($course['id']);

                        if ($details)
                        {
                                $course['total_lessons'] = $details['total_lessons'];
                        }

                        if ($course['total_lessons'] > 0)
                        {
                                $course['progress'] = round(($course['completed_lessons'] / $course['total_lessons']) * 100);
                        }
                }
        }

        public function index()
        {
                $user = $this->getUserData();
                $courses = $this->courseRepository->getAllCourses($user['id']);

                $this->hydrateWithServiceData($courses);

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

                $this->hydrateWithServiceData($courses);

                $this->render('my_courses', [
                        'username' => $user['name'] ? $user['name'] : 'Coder',
                        'ram' => $user['ram'],
                        'courses' => $courses
                ]);
        }

        public function start()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                        $courseId = $_POST['id'];
                        $userId = $_SESSION['user']['id'];

                        $this->courseRepository->setActiveCourse($userId, $courseId);

                        header("Location: /dashboard");
                        exit();
                }

                header("Location: /courses");
        }
}
