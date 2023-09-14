<?php

namespace ControllerMenu;

use Controller\Controller;
use ModelMenu\ModelMenu;

class ControllerMenu extends Controller
{
	private $name;
	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getData()
	{
		$model = new ModelMenu($this->name);
		$menu = $model->getMenu();
		$name = $this->name . '_menu';
		$arData[$name] = $menu;
		return $arData;
	}

	public function getLayout()
	{
		$template = '/templates/menu/Template' . ucfirst($this->name) . 'Menu.php';
		return $template;
	}
}
