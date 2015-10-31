<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib/Router.php';
include 'Lib/Db.php';
include 'Lib/Input.php';
include 'Models/News.php';

use Lib\Router;

$router = new Router('/');

$router->map('GET', '/news', function() {
    echo json_encode(\Models\News::get());
});
$router->map('GET', '/news/[:id]', function($id = 0) {
    echo json_encode(\Models\News::get($id));
});

//$router->map('POST', '/news/[:newsId]', 'News#update');
$router->map('PUT', '/news/[:newsId]', 'News#update');

$router->map('POST', '/news', 'News#create');
$router->map('DELETE', '/news/[:newsId]', 'News#delete');

//used for cleaning the news table after phpunit tests
$router->map('GET', '/my_super_secure_clean_databse_url/[:action]', function($action = null) {
    \Models\News::clean($action);
});

$routes = $router->getRoutes();

$router->match();
