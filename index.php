<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib/Router.php';
include 'Lib/Db.php';
include 'Lib/Input.php';

use Lib\Router;

$router = new Router('/');

$router->map('GET', '/', function() {
	echo 'Home';
});
$router->map('GET', 'home/page/[int:id]', function($id = 0) {
    echo 'home-page-id=' . $id;
});

$router->map('POST', 'news/[int:newsId]', 'Class#method');
$router->map('PUT', 'news/[int:newsId]', 'Class#method');

$router->map('POST', 'news', 'Class#method');
$router->map('DELETE', 'news/[int:newsId]', 'Class#method');

$router->match();