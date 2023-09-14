<?php

namespace ControllerPrice;

use Controller\Controller;
use ModelProducts\ModelProducts;
use ModelSuppliers\ModelSuppliers;

class ControllerPrice extends Controller
{
	private $action;
	private $param;
	private $model;
	private $data;

	public function __construct($action = '', $param = [])
	{
		$this->model = new ModelSuppliers();
		$this->action = $action;
		$this->param = $param;
	}

	public function getData()
	{
		$this->data['button_price'] = [
			'name' => 'Подготовить прайс',
			'href' => '/admin/price/?action=price',
		];

		if (!empty($this->action)) {
			$func = $this->action . 'Data';
			$this->$func();
		}

		return $this->data;
	}

	public function priceData()
	{
		$model_product = new ModelProducts();
		$model_supplier = new ModelSuppliers();
		$arProtucts = $model_product->getQuantities(['product_id']);

		foreach ($arProtucts as $key => $item) {
			$product = $model_product->getProduct($item['product_id']);
			$sklad = $model_supplier->getSklad($item['warehouse_id']);
			$supplier = $model_supplier->getSupplier($sklad['supplier_id']);
			$prices = $model_product->getPrice($item['id']);
//			$code = $model_supplier->getCode($product['id'], $supplier['id']);

			$sklads = $model_supplier->getSkladBySupplier($supplier['id']);

			if (!isset($quantities[$supplier['id']][$product['id']])) {
				$quantities[$supplier['id']][$product['id']] = 0;
				foreach ($sklads as $quan) {
					$quanty = $model_product->getQuantity($product['id'], $quan['id']);


					if (isset($quanty['quantity'])) {
						$sum = preg_replace("/[^0-9]/", '', $quanty['quantity']);
						$quantities[$supplier['id']][$product['id']] += (int)$sum;
					}
				}
			}

			$products[$product['id']][$key] = $prices['price'];

			if ($quantities[$supplier['id']][$product['id']] > 1) {
				$resData['price'][$key]['article'] = $product['kod'];
				$resData['price'][$key]['product_name'] = $product['name'];
				$resData['price'][$key]['supplier_name'] = $supplier['name'];
				$resData['price'][$key]['warehouse_name'] = $sklad['name'];
				$resData['price'][$key]['price'] = $prices['price'];
				$resData['price'][$key]['rrc'] = $prices['rrc'];
				$resData['price'][$key]['quantity'] = $item['quantity'];
				$resData['price'][$key]['quantities'] = $quantities[$supplier['id']][$product['id']];
			}
		}

		foreach ($products as $pices_product) {
			$min_price = min($pices_product);
			$key_price = array_keys($pices_product, $min_price);

			if (isset($key_price['1'])) {
				foreach ($key_price as $sKey) {
					if ($resData['price'][$sKey]['supplier_name'] == '21 поставщик') {
						$key_price = [0 => $sKey];
					}
				}
			}

			if (isset($resData['price'][$key_price[0]])) {
				$this->data['price'][] = $resData['price'][$key_price[0]];
			}
		}
	}

	public function getLayout()
	{
		$template = '/templates/price/TemplatePrice.php';
		$template = str_replace('\\', '/', $template);
		return $template;
	}
}
