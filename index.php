<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib\Router.php';
include 'Models\News.php';

$router = new \Lib\Router('/');

$router->map('GET', '/', function() {
    echo ' homepage ';
});

$router->map('POST', '/news', 'News#create');
$router->map('POST', '/news/[:id]', 'News#update');

$router->map('GET', '/news/[:id]', function($newsId = 0) {
	echo json_encode(\Models\News::get($newsId));
});
$router->map('GET', '/news', function() {
	echo json_encode(\Models\News::get());
});

$routes = $router->getRoutes();

$router->match();
