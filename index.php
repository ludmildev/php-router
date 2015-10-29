<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

class News {
	
	public static function create() {
		return ['success' => 0, 'message' => 'ala bala'];
	}
	public static function update($id) {
		return ['success' => 0, 'message' => 'ala bala', $id];
	}
}
include 'Router.php';

$router = new \Router\Router('/');

$router->map('GET', '/', function() {
    echo ' homepage ';
});

$router->map('POST', '/news', 'News#create');
$router->map('POST', '/news/[:id]', 'News#update');

$router->map('GET', '/user/[:id]', function($userID = null) {
    echo '----------------'.$userID;
});
$router->map('GET', '/user/[:user_id]/comments/[:id]', function($user_id = null, $id = null) {
    echo $id. ' - '. $user_id;
});
$router->map('GET', '/blog/[:blog_id]/comments/[:id]/ttt/[:asd]', function($blog_id = null, $id = null) {
    echo $id. ' - '. $blog_id;
});

$routes = $router->getRoutes();

$router->match();
