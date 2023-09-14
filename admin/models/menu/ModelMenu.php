<?php

namespace ModelMenu;

use DB\PDO;
use Model\Model;

class ModelMenu extends Model
{
	private $nameMenu;
	private $pdo;

	public function __construct($nameMenu)
	{
		$this->pdo = new PDO();
		$this->nameMenu = $nameMenu;
	}

	public function getMenu()
	{
		$query = "SELECT * FROM menu WHERE name_menu='left'";
		$res = $this->pdo->query($query);
		return $res->rows;
	}
}
