<?php

class AppController
{
        public function __construct()
        {
        }

        protected function render(string $template = null, array $variables = [])
        {
                $templatePath = 'public/views/'. $template.'.html';
                $templatePath404 = 'public/views/404.html';
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
                        include $templatePath404;
                        $output = ob_get_clean();
                }
                echo $output;
        }

        protected function validateCsrf()
        {
                $token = $_POST['csrf_token'] ?? '';
                if (!hash_equals($_SESSION['csrf_token'], $token))
                {
                        die("CSRF token validation failed.");
                }
        }

}

?>
