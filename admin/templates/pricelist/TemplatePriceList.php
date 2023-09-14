<h1>Price list</h1>
<br>
<?php
if (isset($arData['suppliers'])) {
	foreach ($arData['suppliers'] as $supplier) {
		echo $supplier['id'] . '---' . $supplier['name'];
	}
}
?>
