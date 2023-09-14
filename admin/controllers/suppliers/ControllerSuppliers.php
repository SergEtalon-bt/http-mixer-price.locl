<?php

namespace ControllerSuppliers;

use Controller\Controller;
use ModelProducts\ModelProducts;
use ModelSuppliers\ModelSuppliers;

class ControllerSuppliers extends Controller
{
	private $action;
	private $param;
	private $model;
	private $uploaddir = '../uploads/price_lists/';
	private $price;
	private $data;
	private $supplier;

	public function __construct($action = '', $param = [])
	{
		$this->model = new ModelSuppliers();
		$this->action = $action;
		$this->param = $param;
	}

	public function getData()
	{
		if (!empty($this->action)) {
			$func = $this->action . 'Data';
			return $this->$func();
		} else {
			$suppliers = $this->model->getSuppliers();
			$this->data['suppliers'] = $suppliers;
			return $this->data;
		}
	}

	public function getLayout()
	{
		if (!empty($this->action)) {
			$template = '/templates/suppliers/TemplateSuppliers' . ucfirst($this->action) . '.php';
			return $template;
		} else {
			$template = '/templates/suppliers/TemplateSuppliers.php';
			return $template;
		}
	}

	private function loadData()
	{
//		var_export($_POST);
//		var_export($_FILES);

		$arrays = [
			'type_upload' => [
				'all' => [
					'del_line_begin' => '3',
					'article' => '0',
					'name' => '5',
					'category' => '7',
					'brand' => '6',
					'rrc' => '8',
					'price' => '9',
					'quantity' =>
						[
							0 =>
								[
									'name' => 'Остаток Бизнес Контроль',
									'column' => '10',
								],
							1 =>
								[
									'name' => 'Остаток поставщика',
									'column' => '11',
								],
						],
				],
			],
		];


		$this->supplier = $this->model->getSupplier($this->param['id']);
		$this->data['select_price_file'] = "Выберете прайс";
		if (isset($_POST['form_upload'])) {
			$this->upload_file();
		}

		if (isset($_POST['form_process'])) {
			$this->process_file();
		}

		$this->data['supplier'] = $this->supplier;
		return $this->data;
	}

	private function settingsData()
	{
//		var_export($_POST);
//		var_export($_FILES);
		$this->supplier = $this->model->getSupplier($this->param['id']);
		$this->data['select_price_file'] = "Выберете прайс";
		if (isset($_POST['supplier_settings'])) {
			$this->settings();
		}
		$this->data['supplier'] = $this->supplier;


		return $this->data;
	}


	/**
	 *  upload_file() - функция загрузки прайса
	 */
	private function upload_file()
	{
		if (isset($_FILES['price_file'])) {
			$this->price = $_FILES['price_file'];
			if ($this->price["size"] < 1024 * 50 * 1024) {
				if ($this->price["name"] != "") {
//					var_export($this->price);
					//узнаём расширение файла
//					$ext = substr($this->price['name'], strpos($this->price['name'], '.'), strlen($this->price['name']) - 1);
					$ext = '.' . pathinfo($this->price['name'], PATHINFO_EXTENSION);
					//Куда закачиваем файл
					$file = $this->uploaddir . $this->supplier['id'] . "_price" . $ext;
					$this->clear_price($this->supplier['id'] . "_price" . $ext);
					$filetypes = ['.csv'];
					if (!in_array($ext, $filetypes)) {
						$this->data['price_error'] = 'Ошибка: Данный формат файлов не поддерживается';
					} else {
						if ($resfile = move_uploaded_file($this->price['tmp_name'], $file)) {
							$this->data['price_msg'] = 'Загружен файл "' . $this->price['name'] . '".';
							unset($this->data['select_price_file']);

							$param_parser = json_decode($this->supplier['param_parser'], true);

							if (!empty($param_parser['type_upload']['all'])) {
								$this->data['button_process'] = [
									'name' => 'Обработать прайс',
									'post_name' => 'form_process',
								];
							} elseif (!empty($param_parser['type_upload']['by_one'])) {
								foreach ($param_parser['type_upload']['by_one'] as $key => $item) {
									$options[] = [
										'name' => $item['name'],
										'value' => $key,
									];
								}
								$this->data['select_process'] =
									[
										'name' => 'Выбрать склад',
										'option_name' => 'sklad',
										'options' => $options,
									];
								$this->data['button_process'] = [
									'name' => 'Обработать прайс',
									'post_name' => 'form_process',
								];
							}
						} else {
//                            var_export($this->price);
							$this->data['price_error'] = 'Ошибка: файл "' . $this->price['name'] . '" не подгружен!.';
						}
					}
				} else {
					$this->data['price_error'] = "Сначала выбирете файл";
				}
			} else {
				$this->data['price_error'] = "Ошибка: Разрешён файл меньше 50 мб";
			}
		}
	}

	/**
	 *  clear_price() - функция удаления прайса
	 */
	private function clear_price($name_price)
	{
		//Проверяем на существование папки
		if (file_exists($this->uploaddir)) {
			//функция очистки папки
			if (file_exists($this->uploaddir . $name_price)) {
				unlink($this->uploaddir . $name_price);
			}
		} else {
			//создаём папку
			mkdir($this->uploaddir, 0700, true);
		}
		if (file_exists($this->uploaddir . $name_price)) {
			unlink($this->uploaddir . $name_price);
		}
	}

	/**
	 *  process_file() - функция обработки прайса
	 */
	private function process_file()
	{
		if (file_exists($this->uploaddir . $this->supplier['id'] . "_price.csv")) {
			$params = json_decode($this->supplier['param_parser'], true);

			if (isset($_POST['sklad'])) {
				$param_parser = $params['type_upload']['by_one'][$_POST['sklad']]['param'];
			}

			if (!empty($params['type_upload']['all'])) {
				$param_parser = $params['type_upload']['all'];
			}

			$etalon = [];
			if (($handle = fopen($this->uploaddir . "etalon.csv", "r")) !== false) {
				$line = 0;
				while (($data = fgetcsv($handle, 10000000, "@")) !== false) {
					if (1 > $line) {
						$line++;
						continue;
					}
					$num = count($data);
					for ($c = 0; $c < $num; $c++) {
						$res = str_getcsv($data[$c], ";");
					}
					if (empty($param_parser['brand'])) {
						if (!empty($res[2])) {
							$etalon[$res[2]] = $res[0];
						}
					} else {
						if (!empty($res[3])) {
							$etalon[$res[3]] = $res[0];
						}
					}
					$res = [];
				}
				fclose($handle);
			}

			if (($handle = fopen($this->uploaddir . $this->supplier['id'] . "_price.csv", "r")) !== false) {
				if (!empty($param_parser)) {
					$line = 0;
					$arrMissingProducts = [];
					while (($data = fgetcsv($handle, 10000000, "@")) !== false) {
						if (isset($param_parser['del_line_begin']) && $param_parser['del_line_begin'] >= $line) {
							$line++;
							continue;
						}
						$num = count($data);
						for ($c = 0; $c < $num; $c++) {
							$res = str_getcsv($data[$c], ";");
						}
						$quantity = [];

						$modelProducts = new ModelProducts();

						foreach ($param_parser['quantity'] as $item) {
							$sklad = $this->model->getSkladBySupplierAndName($this->supplier['id'], $item['name']);
							if (empty($sklad)) {
								$this->model->addSklad($this->supplier['id'], $item['name']);
								$sklad = $this->model->getSkladBySupplierAndName($this->supplier['id'], $item['name']);
							}

							$modelProducts->resetQuantity($sklad['id']);

							$resQuanty = 0;
							if (isset($item['column'])) {
								if (!empty($res[$item['column']])) {
									$resQuanty = $res[$item['column']];
								}
							} else {
								$resQuanty = $item['default'];
							}
							if (!empty($resQuanty)) {
								$quantity[] = [
									'warehouse_id' => $sklad['id'],
									'quantity' => $resQuanty,
								];
							}
						}

						$index = str_replace(' ', '', $res[$param_parser['article']]);

						$code = $this->model->getCodeByArticle($res[$param_parser['article']]);

						if (!empty($quantity[0]) && empty($code)) {
							$arrMissingProducts[] = [
								'article' => $res[$param_parser['article']],
								'product_name' => $res[$param_parser['name']],
							];
							continue;
						}

//						if (!empty($quantity[0]) && !empty($etalon[$index])) {
//
//							if (isset($param_parser['category'])) {
//								$category = $modelProducts->getCategory($res[$param_parser['category']]);
//								if (empty($category)) {
//									$modelProducts->addCategory($res[$param_parser['category']]);
//									$category = $modelProducts->getCategory($res[$param_parser['category']]);
//								}
//							}
//
//
//							if (isset($param_parser['brand'])) {
//								$brand = $modelProducts->getBrand($res[$param_parser['brand']]);
//								if (empty($brand)) {
//									$modelProducts->addBrand($res[$param_parser['brand']]);
//									$brand = $modelProducts->getBrand($res[$param_parser['brand']]);
//								}
//							}
//
//							$arProduct = [
//								'kod' => $etalon[$index],
//								'name' => $res[$param_parser['name']],
//								'category_id' => $category['id'] ?? null,
//								'brand_id' => $brand['id'] ?? null,
//								'active' => 'Y',
//							];
//
//							$product = $modelProducts->getProductByKod($arProduct['kod']);
//							if (empty($product)) {
//								$modelProducts->addProduct($arProduct);
//								$product = $modelProducts->getProductByKod($arProduct['kod']);
//							}
//
//							foreach ($quantity as $quanty) {
//								$arQuantity = [
//									'product_id' => $product['id'],
//									'warehouse_id' => $quanty['warehouse_id'],
//									'quantity' => $quanty['quantity'],
//								];
//
//								$resQuantity = $modelProducts->getQuantity($arQuantity['product_id'], $arQuantity['warehouse_id']);
//								if (empty($resQuantity)) {
//									$modelProducts->addQuantity($arQuantity);
//									$resQuantity = $modelProducts->getQuantity($arQuantity['product_id'], $arQuantity['warehouse_id']);
//								} elseif ($resQuantity['quantity'] != $arQuantity['quantity']) {
//									$modelProducts->updateQuantity($resQuantity['id'], $arQuantity['quantity']);
//									$resQuantity = $modelProducts->getQuantity($arQuantity['product_id'], $arQuantity['warehouse_id']);
//								}
//
//								$arPrices = [
//									'availability_id' => $resQuantity['id'],
//									'price' => preg_replace("/[^,.0-9]/", '', $res[$param_parser['price']]),
//									'rrc' => preg_replace("/[^,.0-9]/", '', $res[$param_parser['rrc']]),
//								];
//
//								$price = $modelProducts->getPrice($arPrices['availability_id']);
//								if (empty($price)) {
//									$modelProducts->addPrice($arPrices);
//								} elseif ($price['price'] != $res[$param_parser['price']] && $price['rrc'] != $res[$param_parser['rrc']]) {
//									$modelProducts->updatePrice($arPrices);
//								}
//							}
//
////                            $arCodes = [
////                                'product_id' => $product['id'],
////                                'supplier_id' => $this->supplier['id'],
////                                'article' => $res[$param_parser['article']],
////                            ];
////
////                            $code = $this->model->getCode($arCodes['product_id'], $arCodes['supplier_id']);
////                            if (empty($code)) {
////                                $this->model->addCode($arCodes);
////                            }
//						}

						$res = [];
						unset($modelProducts);
					}

//					echo '<pre>';
//					var_export($param_parser);
//					echo '</pre>';


					if (!empty($arrMissingProducts)) {
						$this->data['missing_products'] = $arrMissingProducts;

						// Сохраняем итог в файл
						$nameFile = $this->supplier['name'];
						foreach ($param_parser['quantity'] as $ns) {
							$nameFile .= '_' . $ns['name'];
						}

						$fp = fopen(MISSING_PRICE . 'missing_price_' . $nameFile .'.csv', 'w');
						foreach ($arrMissingProducts as $p => $titlesItem) {
							$itog[$p] = array_map(function ($arg) {
								return iconv("UTF-8", "Windows-1251//IGNORE", $arg);
							}, (array)$titlesItem);
						}
						$arHeaders = [
							0 => iconv("UTF-8", "Windows-1251//IGNORE", 'Артикул'),
							1 => iconv("UTF-8", "Windows-1251//IGNORE", 'Наименование'),
						];
						fwrite($fp, implode(';', $arHeaders) . "\r\n");
						foreach ($itog as $fields) {
							//$mas[] = fputcsv($fp, (array)$fields, ';');
							fwrite($fp, implode(';', (array)$fields) . "\r\n");
						}
						$this->data['button_price_download'] = [
							'name' => 'Скачать файл',
							'href' => '../' . DOWNLOAD_PRICE . 'price.csv',
						];
					}

				} else {
					$this->data['price_error'] = 'Некорректные настройки.';
				}
				$this->data['price_processed'] = 'Прайс обработан.';
				$this->model->updateDataLoadPrice($this->supplier['id']);
				fclose($handle);


			}
		} else {
			$this->data['price_error'] = "Загрузите прайс";
			$this->data['select_price_file'] = "Выберете прайс";
		}
	}

	private
	function settings()
	{

	}
}
