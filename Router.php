<?php
namespace Router;

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
		$this->_routes[$route] = array($method, $route, $target);
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

        foreach($this->_routes as $handler)
        {
            list($_method, $_route, $_target) = $handler;

            if ($serverRequestMethod != $_method) {
                continue;
            }
			
            $match = preg_match($this->compileRoute($_route), $requestUrl, $params);

			if(($match == true || $match > 0))
			{
				if($params) {
					foreach($params as $key => $value) {
						if(is_numeric($key)) 
							unset($params[$key]);
					}
				}

				if(is_callable($_target) && $_method == 'GET')
				{
					call_user_func_array($_target, $params);
					return;
				}
				elseif ($_method == 'POST')
				{
					$_target = explode('#', $_target);
					$model = $_target[0];
					$method = isset($_target[1]) ? $_target[1] : 'index';
					
					$result = forward_static_call_array([$model, $method], $params);
					
					echo json_encode($result);
					return;
				}
				else {
					header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
					return;
				}
			}
        }
		
		header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		return;
	}
    
    private function compileRoute($route)
    {
        $_match = preg_match_all('/(\[:(.*?)\])/', $route, $matches, PREG_SET_ORDER);

		if ($_match)
        {
			foreach($matches as $match)
            {
				list($search, $pre, $value) = $match;
                
				if ($pre === '.') {
					$pre = '\.';
				}

				$pattern = '(?:('
						. ($value !== '' ? "?<$value>" : null)
						. '[^/\.]++'
						. '))';
				$route = str_replace($search, $pattern, $route);
			}
		}
        
		return "`^{$route}$`u";
	}
}
























