<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib/Router.php';
include 'Lib/Db.php';
include 'Lib/Input.php';

use Lib\Router;

//USE:
// url: [METHOD] [URL] [ACTION:callable function]
// url: [METHOD] [URL] [ACTION:Class#method]

$router = new Router('/router/trunk/');

$router->map('GET', '/news', function() {
	echo 'news';
});
$router->map('GET', '/news/[:newsId]', function($newsId = 0) {
	echo json_encode([$newsId]);
});

$router->match();
