<?php

namespace ControllerProducts;

use Controller\Controller;
use ModelProducts\ModelProducts;

class ControllerProducts extends Controller
{
	public function getData()
	{
		$model = new ModelProducts();
		$products = $model->getData();
		$arData['products'] = $products;
		return $arData;
	}

	public function getLayout()
	{
		$template = '/templates/products/TemplateProducts.php';
		$template = str_replace('\\', '/', $template);
		return $template;
	}
}
