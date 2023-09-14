<?php

namespace Router;

class Router
{
	private $required_uri = 'index.php';
	private $param;

	public function __construct()
	{
//		$this->required_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		$this->required_uri = $_SERVER["REDIRECT_URL"];
		if (isset($_SERVER['REDIRECT_QUERY_STRING'])) {
			parse_str($_SERVER['REDIRECT_QUERY_STRING'], $this->param);
//		$this->param = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		}
	}

	public function getController()
	{
		$action = '';
		if (!empty($this->param)) {
			$action = $this->param['action'];
			unset($this->param['action']);
		}
//		echo '<pre>';
//		var_export($this->param);
//		echo '</pre>';

		$path = explode('/', $this->required_uri);
		$path[2] = explode('-', $path[2]);
//		print_r($path[2]);
		if (isset($path[2][1])) {
			$path[2] = $path[2][0] . ucfirst($path[2][1]);
		} else {
			$path[2] = $path[2][0];
		}

		if ($path[2] == '' || $path[2] == 'index.php') {
			return;
		} else {
			$controllerName = 'Controller' . ucfirst($path[2]) . '\\' . 'Controller' . ucfirst($path[2]);
//			print_r($controllerName);
			$controller = new $controllerName($action, $this->param);
			return $controller;
		}
	}

	public function getControllerMenu($name)
	{
		$controllerName = 'ControllerMenu' . '\\' . 'ControllerMenu';
		$controller = new $controllerName($name);
//		var_export($controller);
		return $controller;
	}

	public function getUri()
	{
		return $this->required_uri;
	}
}
