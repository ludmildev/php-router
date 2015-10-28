<?php
namespace Router;

class Router {

	private $_basePath = '';
	private $_routes = [];
    protected $matchTypes = array(
		//'i'  => '[0-9]++',
		//'a'  => '[0-9A-Za-z]++',
		//'h'  => '[0-9A-Fa-f]++',
		//'*'  => '.+?',
		//'**' => '.++',
		''   => '[^/\.]++'
	);

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
        
		$requestUrl = substr($serverRequestUrl, strlen($this->getBasePath()));

		if (($strpos = strpos($requestUrl, '?')) !== false) {
			$requestUrl = substr($requestUrl, 0, $strpos);
		}

        $_REQUEST = array_merge($_GET, $_POST);

        foreach($this->_routes as $handler)
        {
            list($_method, $_route, $_target) = $handler;

            if ($serverRequestMethod != $_method) {
                header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
                continue;
            }
            
            $route = '';
            $regex = false;
            $j = 0;
            $i = 0;
            $n = isset($_route[0]) ? $_route[0] : null;

            while (true)
            {
                if (!isset($_route[$i])) {
                    break;
                } elseif (false === $regex) {
                    $c = $n;
                    $regex = $c === '[';
                    if (false === $regex && false !== isset($_route[$i+1])) {
                        $n = $_route[$i + 1];
                    }
                    if (false === $regex && $c !== '/' && (!isset($requestUrl[$j]) || $c !== $requestUrl[$j])) {
                        continue 2;
                    }
                    $j++;
                }
                $route .= $_route[$i++];
            }

            $match = preg_match($this->compileRoute($route), $requestUrl, $params);
        }
        
        if(($match == true || $match > 0)) {

            if($params) {
                foreach($params as $key => $value) {
                    if(is_numeric($key)) unset($params[$key]);
                }
            }

            $obj = array(
                'target' => $_target,
                'params' => $params
            );

            // call closure or throw 404 status
            if( $obj && is_callable( $obj['target'] ) ) {
                call_user_func_array($obj['target'], $obj['params'] );
            } else {
                // no route was matched
                header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
            }
        }
	}
    
    private function compileRoute($route) {
        $match = preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER);
		if ($match) {

			$matchTypes = $this->matchTypes;
			foreach($matches as $match) {
				list($block, $pre, $type, $param, $optional) = $match;
				if (isset($matchTypes[$type])) {
					$type = $matchTypes[$type];
				}
				if ($pre === '.') {
					$pre = '\.';
				}

				//Older versions of PCRE require the 'P' in (?P<named>)
				$pattern = '(?:'
						. ($pre !== '' ? $pre : null)
						. '('
						. ($param !== '' ? "?P<$param>" : null)
						. $type
						. '))'
						. ($optional !== '' ? '?' : null);

				$route = str_replace($block, $pattern, $route);
			}

		}
        
		return "`^$route$`u";
	}
}
























