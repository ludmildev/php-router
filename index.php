<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Router.php';

$router = new \Router\Router('/test');

$router->map('GET', '/user/[:user_id]/comments/[:id]', function($user_id, $id) {
    echo $id. ' - '. $user_id;
});
$router->map('GET', '/blog/[:blog_id]/comments/[:id]', function($blog_id, $id) {
    echo $id. ' - '. $user_id;
});

$routes = $router->getRoutes();

$match = $router->match();