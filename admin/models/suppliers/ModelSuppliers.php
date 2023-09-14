<?php

namespace ModelSuppliers;

use DB\PDO;
use Model\Model;

class ModelSuppliers extends Model
{
    private $suppliers;
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO();
    }

    public function getSuppliers()
    {
        $query = "SELECT * FROM suppliers";
        $res = $this->pdo->query($query);
        return $res->rows;
    }

    public function getSupplier($id)
    {
        $query = "SELECT * FROM suppliers WHERE id = $id";
        $res = $this->pdo->query($query);
        return $res->row;
    }

    public function deactivateProducts(int $supplier_id)
    {
        $arWherehouseId = $this->pdo->query("SELECT id FROM warehouses WHERE supplier_id = " . $supplier_id)->rows;
        $arProductId = [];
        foreach ($arWherehouseId as $warehouse_id) {
            $arr = $this->pdo->query("SELECT product_id FROM availability WHERE warehouse_id = " . $warehouse_id['id'])->rows;
            $arProductId = array_merge_recursive($arProductId, $arr);
//			var_export($arr);
        }
        foreach ($arProductId as $product_id) {
            $this->pdo->query("UPDATE products SET active = 'N'  WHERE id = " . $product_id['product_id']);
        }
//		var_export($arProductId);

    }

    public function getSklad(int $id)
    {
        $query = "SELECT * FROM warehouses WHERE id = " . $id;
        $res = $this->pdo->query($query);
        return $res->row;
    }

    public function getSkladBySupplierAndName($supplier_id, $name)
    {
        $query = "SELECT * FROM warehouses WHERE supplier_id = " . (int)$supplier_id . " AND name = '" . $this->pdo->escape($name) . "'";
        $res = $this->pdo->query($query);
        return $res->row;
    }

    public function getSkladBySupplier(int $supplier_id)
    {
        $query = "SELECT * FROM warehouses WHERE supplier_id = " . $supplier_id;
        $res = $this->pdo->query($query);
        return $res->rows;
    }

    public function addSklad(int $supplier_id, $name)
    {
        $query = "INSERT INTO warehouses (supplier_id, name) VALUES (" . $supplier_id . ", '" . $this->pdo->escape($name) . "')";
        $this->pdo->query($query);
    }

    public function delSclad(int $id)
    {
        $query = "DELETE FROM warehouses WHERE id = (int)$id";
        $this->pdo->query($query);
    }

    public function getCode(int $product_id, int $supplier_id)
    {
        $query = "SELECT * FROM codes WHERE product_id = '" . $product_id . "' AND supplier_id = '" . $supplier_id . "'";
        $res = $this->pdo->query($query);
        return $res->row;
    }

    public function getCodeByArticle($article)
    {
        $query = "SELECT * FROM codes WHERE article = '" . $this->pdo->escape($article) . "'";
        $res = $this->pdo->query($query);
        return $res->row;
    }

    public function addCode(array $arCodes)
    {
        $product_id = $arCodes['product_id'];
        $supplier_id = $arCodes['supplier_id'];
        $article = $arCodes['article'];
        $query = "INSERT INTO codes (product_id, supplier_id, article) VALUES (" . (int)$product_id . ", " . (int)$supplier_id . ", '" . $this->pdo->escape($article) . "')";
//		var_export($query);
//		echo '<br>';
        $this->pdo->query($query);
    }

    public function delCode(int $id)
    {
        $query = "DELETE FROM codes WHERE id = " . $id;
        $this->pdo->query($query);
    }

    public function updateDataLoadPrice(int $id_supplier)
    {
        $query = "UPDATE suppliers SET data_load_price = '" . date('Y-m-d H:i:s') . "' WHERE id = " . $id_supplier;
        $this->pdo->query($query);
    }
}
