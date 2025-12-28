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
                $user = $this->getUserData();
                if ($user['ram'] <= 0) {
                         header("Location: /dashboard");
                         exit();
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']))
                {
                        $courseId = $_POST['id'];
                        $userId = $_SESSION['user']['id'];

                        $this->courseRepository->setActiveCourse($userId, $courseId);

                        header("Location: /dashboard");
                        exit();
                }

                header("Location: /courses");
        }

        public function lesson()
        {
                if (!isset($_GET['id']))
                {
                        header("Location: /dashboard");
                        exit();
                }

                $userId = $_SESSION['user']['id'];
                $user = $this->userRepository->getUserById($userId);

                if ($user['ram'] <= 0) {
                        header("Location: /dashboard");
                        exit();
                }

                $courseId = (int)$_GET['id'];
                $courseProgress = $this->courseRepository->getUserCourse($userId, $courseId);

                if (!$courseProgress)
                {
                        header("Location: /courses");
                        exit();
                }

                $currentLessonNum = $courseProgress['completed_lessons'] + 1;
                $lessonData = $this->contentService->getLesson($courseId, $currentLessonNum);

                if (!$lessonData)
                {
                        header("Location: /dashboard");
                        exit();
                }

                $this->courseRepository->setActiveCourse($userId, $courseId);

                $this->render('lesson', [
                        'lesson' => $lessonData,
                        'lessonNumber' => $currentLessonNum,
                        'ram' => $user['ram']
                ]);
        }

        public function completeLesson()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id']))
                {
                        $courseId = $_POST['course_id'];
                        $userId = $_SESSION['user']['id'];

                        $courseProgress = $this->courseRepository->getUserCourse($userId, $courseId);
                        if (!$courseProgress)
                        {
                                header("Location: /courses");
                                exit();
                        }

                        $this->courseRepository->incrementProgress($userId, $courseId);

                        header("Location: /lesson?id=" . $courseId);
                        exit();
                }
                header("Location: /dashboard");
        }

        public function deductRam()
        {
                $input = json_decode(file_get_contents('php://input'), true);

                if (!$input) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid request']);
                    return;
                }

                $userId = $_SESSION['user']['id'];
                $newRam = $this->userRepository->deductRam($userId);

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'ram' => $newRam]);
        }
}
