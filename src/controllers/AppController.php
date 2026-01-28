<?php

class AppController
{
        protected $jsonInput;

        public function __construct()
        {
                if ($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                        $this->validateCsrf();
                }
        }

        protected function render(string $template = null, array $variables = [])
        {
                $templatePath = 'public/views/'. $template.'.html';
                $errorPath = 'public/views/error.php';
                $output = "";

                if(file_exists($templatePath))
                {
                        extract($variables);
                        ob_start();
                        include $templatePath;
                        $output = ob_get_clean();
                }
                else
                {
                        ob_start();
                        $errorCode = 404;
                        $errorMessage = "Page Not Found";
                        include $errorPath;
                        $output = ob_get_clean();
                }
                echo $output;
        }

        protected function validateCsrf()
        {
                $publicActions = ["login", "register", "forgotPassword"];

                $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
                $currentAction = explode('/', $path)[0];
                if ($currentAction === '') $currentAction = 'dashboard';

                if (in_array($currentAction, $publicActions))
                {
                        return;
                }

                $token = $_POST['csrf_token'] ?? '';

                if (empty($token))
                {
                        $this->jsonInput = json_decode(file_get_contents('php://input'), true);
                        $token = $this->jsonInput['csrf_token'] ?? '';
                }

                if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token))
                {
                        http_response_code(403);
                        $errorCode = 403;
                        $errorMessage = "Forbidden";
                        include 'public/views/error.php';
                        exit();
                }
        }
}
?>
