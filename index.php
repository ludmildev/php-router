<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Lib/Router.php';
include 'Lib/Db.php';
include 'Lib/Input.php';
include 'Lib/Template.php';

use Lib\Router;
use Lib\Template;

//USE:
// url: [METHOD] [URL] [ACTION:callable function]
// url: [METHOD] [URL] [ACTION:Class#method]

$router = new Router('/router/trunk/');

$template = new Template('C:\wamp\www\test\router\trunk\Views');

$router->map('GET', '/news', function($id = 0)
{
	
});
$router->map('GET', '/news/[:newsId]', function($newsId = 0)
{
	global $template;
	
	$params['id'] = $newsId;
	
	echo $template
	->setFolder('home')
	->load('home')
	->render($params);
});

$router->match();
