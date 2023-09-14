<?php

namespace Layer;

class Layer
{
	private $layers;
	private $layout = '';

//	private $data = [];

	public function __construct($layers)
	{
		$this->layers = $layers;
	}

	public function getLayout()
	{

		foreach ($this->layers as $layer) {
			ob_start();
			$arData = [];

			$arData = $layer['data'];

			$path = '../admin' . $layer['layout'];
			if (file_exists($path)) {
				require_once $path;
			}
			$this->layout .= ob_get_contents();
			ob_end_clean();

		}
//		var_export($this->layout);
		return $this->layout;

	}
}
