<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib\Router.php';
include 'Lib\Db.php';
include 'Models\News.php';

use Lib\Router;

$router = new Router('/');

$router->map('GET', '/', function() {
    echo ' homepage ';
});

$router->map('POST', '/news', 'News#create');
$router->map('POST', '/news/[:newsId]', 'News#update');

$router->map('GET', '/news/[:newsId]', function($newsId = 0) {
	echo json_encode(\Models\News::get($newsId));
});
$router->map('GET', '/news', function() {
	echo json_encode(\Models\News::get());
});

$routes = $router->getRoutes();

$router->match();
