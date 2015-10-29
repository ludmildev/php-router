<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Router.php';

$router = new \Router\Router('/test');

$router->map('GET', '/', function() {
    echo ' homepage ';
});
$router->map('GET', '/user/[:user_id]/comments/[:id]', function($user_id = null, $id = null) {
    echo $id. ' - '. $user_id;
});
$router->map('GET', '/user/[:user_id]', function($userID = null) {
    echo '----------------'.$userID;
});
$router->map('GET', '/blog/[:blog_id]/comments/[:id]/ttt/[:asd]', function($blog_id = null, $id = null) {
    echo $id. ' - '. $blog_id;
});

$routes = $router->getRoutes();

$match = $router->match();

//var_dump($routes);