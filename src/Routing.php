<?php

require_once "controllers/SecurityController.php";
require_once "controllers/DashboardController.php";
require_once "controllers/CourseController.php";

class Routing
{
        public static $routes = [
                "login" => [
                        "controller" => "SecurityController",
                        "action" => "login"
                ],
                "register" => [
                        "controller" => "SecurityController",
                        "action" => "register"
                ],
                "dashboard" => [
                        "controller" => "DashboardController",
                        "action" => "index"
                ],
                "courses" => [
                        "controller" => "CourseController",
                        "action" => "index"
                ],
                "settings" => [
                        "controller" => "DashboardController",
                        "action" => "settings"
                ]
        ];

        public static function run($path)
        {
                $action = explode('/', $path)[0];
                if ($action === '')
                {
                        $action = 'dashboard';
                }

                self::enforceAuthentication($action);

                if (array_key_exists($action, Routing::$routes))
                {
                        $controller = Routing::$routes[$action]["controller"];
                        $method = Routing::$routes[$action]["action"];

                        $controllerObj = new $controller;
                        $controllerObj->$method();
                }
                else
                {
                        http_response_code(404);
                        include 'public/views/404.html';
                }
        }

        private static function enforceAuthentication($action)
        {
                $publicActions = ['login', 'register'];
                $isAuthenticated = isset($_SESSION['user']['id']);

                if (!$isAuthenticated && !in_array($action, $publicActions))
                {
                        header('Location: /login');
                        exit();
                }

                if ($isAuthenticated && in_array($action, $publicActions))
                {
                        header('Location: /dashboard');
                        exit();
                }

        }
}
?>
