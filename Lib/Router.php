<?php
namespace Lib;

class Router {

	private $_basePath = '';
	private $_routes = [];

	public function __construct($basePath = '/', $routes = [])
	{
		$this->setBasePath($basePath);
        
        if (!empty($routes))
            $this->setRoutes($routes);
	}

	public function getRoutes() {
		return $this->_routes;
	}
	private function setRoutes($routes = [])
	{
		if (empty($routes)) {
			throw new \Exception('Routes should be a non empty array.');
		}

		foreach($routes as $route)
		{
			if (count($route) < 3) {
				throw new \Exception('Invalid Route.');
			}

            list($method, $_route, $target) = $route;

			$this->map($method, $_route, $target);
		}
	}

	private function setBasePath($path) {
		$this->_basePath = $path;
	}
	public function getBasePath() {
		return $this->_basePath;
	}

	public function map($method, $route, $target)
	{
		$this->_routes[] = array($method, $route, $target);
	}

	public function match()
	{
        $match = false;
		$serverRequestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $serverRequestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

		if (in_array('/', array($serverRequestUrl, $this->getBasePath())))
			$requestUrl = $serverRequestUrl;
		else
			$requestUrl = substr($serverRequestUrl, strlen($this->getBasePath()));
		

		if (($strpos = strpos($requestUrl, '?')) !== false) {
			$requestUrl = substr($requestUrl, 0, $strpos);
		}

        foreach($this->_routes as $route)
        {
            list($_method, $_route, $_target) = $route;

            if ($serverRequestMethod != $_method) {
                continue;
            }

            $_routeParts = explode('/', $_route);
            $_uriParts = explode('/', $requestUrl);

			array_splice($_routeParts, 0, 1);
			array_splice($_uriParts, 0, 1);
			
			var_dump($_routeParts);
			var_dump($_uriParts);

			if (count($_routeParts) != count($_uriParts)) {
				continue;
			}
			
			if (count($_routeParts) == 1 && count($_uriParts) == 1 && $_routeParts != $_uriParts) {
				continue;
			}

			$thisRoute = true;
			$params = array();
			
            foreach($_routeParts as $i => $part)
            {
                if ($part == $_uriParts[$i]) {
					continue;
                }
				
				$partLength = strlen($part) - 1;
				
				if (stripos($part, '[') == 0 && stripos($part, ']') == $partLength)
				{
					$variables = explode(':', substr($part, 1, $partLength-1));
					
					if ($variables[0] == 'int' && !is_numeric($_uriParts[$i])) {
						$thisRoute = false;
						break;
					}
					elseif ($variables[0] == 'str' && is_numeric($_uriParts[$i])) {
						$thisRoute = false;
						break;
					}
					
					$params[$variables[1]] = $_uriParts[$i];
				}
            }
			
			if (!$thisRoute)
				continue;
			
			if(is_callable($_target) && $_method == 'GET')
			{
				call_user_func_array($_target, $params);
				return;
			}
			elseif (in_array($_method, ['POST', 'DELETE', 'PUT']))
			{
				$_target = explode('#', $_target);
				
				if (!isset($_target[0]) || !isset($_target[1])) {
					throw new \Exception('Invalid Model Data.');
				}
				
				//TODO: check if class and method exists and is callable
				
				$model = $_target[0];
				$method = $_target[1];
				
				$result = forward_static_call_array(['\\Models\\'.$model, $method], $params);
				
				echo json_encode($result);
				return;
			}
			else {
				header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
				return;
			}
        }
		
		header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		return;
	}
}