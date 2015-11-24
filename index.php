<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib/Router.php';
include 'Lib/Db.php';
include 'Lib/Input.php';
include 'Models/News.php';

use Lib\Router;

$router = new Router('/');

$router->map('GET', 'news', function() {
    //echo json_encode(\Models\News::get());
	echo 1;
});
$router->map('GET', 'news/[int:id]', function($id = 0) {
    echo json_encode(\Models\News::get($id));
});

//$router->map('POST', '/news/[:newsId]', 'News#update');
$router->map('PUT', 'news/[int:newsId]', 'News#update');

$router->map('POST', 'news', 'News#create');
$router->map('DELETE', 'news/[int:newsId]', 'News#delete');

$routes = $router->getRoutes();

$router->match();
