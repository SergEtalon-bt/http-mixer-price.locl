<?php

spl_autoload_register(function ($class) {
	$path = '../core/lib/' . $class . '.php';
	$path = str_replace('\\', '/', $path);
//	print_r($path);
//	echo '<br>';
	if (file_exists($path)) {
		require_once $path;
	}
});
spl_autoload_register(function ($class) {
	$arPath = preg_split('/(?<=[a-z])(?=[A-Z])/u', $class);
	$path = mb_strtolower($arPath[0]) . 's' . '/' . mb_strtolower($arPath[2]) . '/' . $arPath[0] . $arPath[2] . '.php';
	$path = str_replace('\\', '/', $path);
//	print_r($path);
//	echo '<br>';
	if (file_exists($path)) {
		require_once $path;
	}
});
spl_autoload_register(function ($class) {
	$arPath = preg_split('/(?<=[a-z])(?=[A-Z])/u', $class);
	$path = '../' . mb_strtolower($arPath[0]) . 's' . '/' . mb_strtolower($arPath[2]) . '/' . $arPath[0] . $arPath[2] . '.php';
	$path = str_replace('\\', '/', $path);
//	print_r($path);
//	echo '<br>';
	if (file_exists($path)) {
		require_once $path;
	}
});
spl_autoload_register(function ($class) {
	$arPath = preg_split('/(?<=[a-z])(?=[A-Z])/u', $class);
	$path = mb_strtolower($arPath[0]) . 's' . '/' . mb_strtolower($arPath[4]) . '/' . $arPath[0] . $arPath[1] . $arPath[4] . '.php';
	$path = str_replace('\\', '/', $path);
//	print_r($arPath);
//	echo '<br>';
	if (file_exists($path)) {
		require_once $path;
	}
});
spl_autoload_register(function ($class) {
	$arPath = preg_split('/(?<=[a-z])(?=[A-Z])/u', $class);
	$path = mb_strtolower($arPath[0]) . 's' . '/' . mb_strtolower($arPath[3]) . mb_strtolower($arPath[4]) . '/' . $arPath[0] . $arPath[1] . $arPath[4] . '.php';
	$path = str_replace('\\', '/', $path);
//	print_r($arPath);
//	echo '<br>';
	if (file_exists($path)) {
		require_once $path;
	}
});

use Layer\Layer;
use Router\Router;

function getTemplateFromController ($controller) {
	if ($controller instanceof Controller\Controller) {
		$leyers = [];
		$leyers[] = ['layout' => $controller->getLayout('left'), 'data' => $controller->getData('left')];
		$layer = new Layer($leyers);
		return $layer->getLayout();
	} else {
		return '';
	}
}

$work_area = '';
$router = new Router();

$controller = $router->getControllerMenu('left');
$header_left_menu = getTemplateFromController ($controller);

$controller = $router->getController();
$work_area = getTemplateFromController ($controller);
