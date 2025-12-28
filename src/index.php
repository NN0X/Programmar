<?php

require_once "Routing.php";

$path = parse_url($_SERVER['REQUEST_URI']);
$path = trim($path['path'], '/');

session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
]);
session_start();
if (empty($_SESSION['csrf_token']))
{
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

Routing::run($path);
?>
