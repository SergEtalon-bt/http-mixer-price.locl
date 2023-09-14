<?php

namespace ModelProducts;

use Model\Model;
use DB\PDO;

class ModelProducts extends Model
{
	private $pdo;

	public function __construct()
	{
		$this->pdo = new PDO();
	}

	public function getData()
	{
		$query = "SELECT * FROM products";
		$res = $this->pdo->query($query);
		return $res->rows;
	}

	public function getProduct(int $id)
	{
		$query = "SELECT * FROM products WHERE id = " . $id;
		$res = $this->pdo->query($query);
		return $res->row;
	}

	public function getProductByKod(string $kod)
	{
		$query = "SELECT * FROM products WHERE kod = '" . $this->pdo->escape($kod) . "'";
		$res = $this->pdo->query($query);
		return $res->row;
	}

	public function addProduct(array $arProduct)
	{
		$kod = $this->pdo->escape($arProduct['kod']);
		$name = $this->pdo->escape($arProduct['name']);
		$category_id = !empty($this->pdo->escape($arProduct['category_id'])) ? $this->pdo->escape($arProduct['category_id']) : 'NULL';
		$brand_id = !empty($this->pdo->escape($arProduct['brand_id'])) ? $this->pdo->escape($arProduct['brand_id']) : 'NULL';
		$active = $this->pdo->escape($arProduct['active']);

		$query = "INSERT INTO products (kod, name, category_id, brand_id, date_change, active) VALUES ('" . $kod . "', '" . $name . "', " . $category_id . ", " . $brand_id . ", '" . date('Y-m-d H:i:s') . "', '" . $active . "')";
		$this->pdo->query($query);
	}

	public function activateProduct(int $id)
	{
		$query = "UPDATE products SET active = 'Y'  WHERE id = " . $id;
		$this->pdo->query($query);
	}

	public function delProduct(int $id)
	{
		$query = "DELETE FROM products WHERE id = " . $id;
		$this->pdo->query($query);
	}

	public function getCategory($name)
	{
		$query = "SELECT * FROM categories WHERE name = '" . $this->pdo->escape($name) . "'";
		$res = $this->pdo->query($query);
		return $res->row;
	}

	public function addCategory(string $name, int $parent = null)
	{
		if (!empty($parent_id)) {
			$query = "INSERT INTO categories (name, parent) VALUES ('" . $this->pdo->escape($name) . "', " . $parent_id . ")";
		} else {
			$query = "INSERT INTO categories (name) VALUES ('" . $this->pdo->escape($name) . "')";
		}
		$this->pdo->query($query);
	}

	public function delCategory(int $id)
	{
		$query = "DELETE FROM categories WHERE id = " . $id;
		$this->pdo->query($query);
	}

	public function getBrand(string $name)
	{
		$query = "SELECT * FROM brands WHERE name = '" . $this->pdo->escape($name) . "'";
		$res = $this->pdo->query($query);
		return $res->row;
	}

	public function addBrand(string $name)
	{
		$query = "INSERT INTO brands (name) VALUES ('" . $this->pdo->escape($name) . "')";
		$this->pdo->query($query);
	}

	public function delBrand(int $id)
	{
		$query = "DELETE FROM brands WHERE id = " . $id;
		$this->pdo->query($query);
	}

	public function getQuantities(array $group = [])
	{
		$query = "SELECT * FROM availability";
		if (!empty($group)) {
			$query .= " ORDER BY ";
			foreach ($group as $item) {
				$query .= " $item, ";
			}
			$query = substr(trim($query), 0, -1);
		}
		$res = $this->pdo->query($query);
		return $res->rows;
	}

	public function getQuantity(int $product_id, int $warehouse_id)
	{
		$query = "SELECT * FROM availability WHERE product_id = $product_id AND warehouse_id = $warehouse_id";
		$res = $this->pdo->query($query);
		return $res->row;
	}

	public function addQuantity(array $arQuantity)
	{
		$product_id = $arQuantity['product_id'];
		$warehouse_id = $arQuantity['warehouse_id'];
		$quantity = $arQuantity['quantity'];
		$query = "INSERT INTO availability (product_id, warehouse_id, quantity) VALUES (" . (int)$product_id . ", " . (int)$warehouse_id . ", '" . $this->pdo->escape($quantity) . "')";
//		var_export($query);
//		echo '<br>';
		$this->pdo->query($query);
	}

	public function resetQuantity(int $warehouse_id)
	{
		$query = "UPDATE availability SET quantity = 0 WHERE warehouse_id = " . $warehouse_id;
		$this->pdo->query($query);
	}

	public function updateQuantity(int $id, string $quantity)
	{
		$query = "UPDATE availability SET quantity = '" . $this->pdo->escape($quantity) . "' WHERE id = " . $id;
		$this->pdo->query($query);
	}

	public function delQuantity(int $id)
	{
		$query = "DELETE FROM availability WHERE id = " . $id;
		$this->pdo->query($query);
	}

	public function getPrice(int $availability_id)
	{
		$query = "SELECT * FROM prices WHERE availability_id = " . $availability_id;
		$res = $this->pdo->query($query);
		return $res->row;
	}

	public function addPrice(array $arPrices)
	{
		$availability_id = $arPrices['availability_id'];
		$price = $arPrices['price'];
		$rrc = $arPrices['rrc'];
		$query = "INSERT INTO prices (availability_id, price, rrc) VALUES (" . (int)$availability_id . ", '" . $this->pdo->escape($price) . "', '" . $this->pdo->escape($rrc) . "')";
//        var_export($query);
//        echo '<br>';
		$this->pdo->query($query);
	}

	public function updatePrice(array $arPrices)
	{
		$availability_id = $arPrices['availability_id'];
		$price = $arPrices['price'];
		$rrc = $arPrices['rrc'];
		$query = "UPDATE prices SET price = '" . $this->pdo->escape($price) . "', rrc = '" . $this->pdo->escape($rrc) . "'WHERE availability_id = " . $availability_id;
		$this->pdo->query($query);
	}

	public function delPrice(int $id)
	{
		$query = "DELETE FROM prices WHERE id = " . $id;
		$this->pdo->query($query);
	}
}
