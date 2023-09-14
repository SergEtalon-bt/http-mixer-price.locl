<div class="container-fluid">
	<div class="row">
		<div class="col-10">
			<a href="<?= $arData['button_price']['href']; ?>">
				<button type="button" class="btn btn-info mx-3"><?= $arData['button_price']['name']; ?></button>
			</a>
			<? if (isset($arData['button_price_download'])): ?>
				<a href="<?= $arData['button_price_download']['href']; ?>">
					<button type="button" class="btn btn-info mx-3"><?= $arData['button_price_download']['name']; ?></button>
				</a>
			<? endif; ?>
		</div>
	</div>
	<? if (isset($arData['price'])): ?>
		<div class="row">
			<div class="col-5">
				<h1>Price</h1>
			</div>
		</div>
		<table class="table">
			<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Артикул</th>
				<th scope="col">Название</th>
				<th scope="col">Поставщик</th>
				<th scope="col">Склад</th>
				<th scope="col">Минимальная цена</th>
				<th scope="col">РРЦ</th>
				<th scope="col">Остаток</th>
				<th scope="col">Остаток у поставщика</th>
			</tr>
			</thead>
			<tbody>

			<? if (isset($arData['price'])): ?>
				<? $i = 1; ?>
				<? foreach ($arData['price'] as $price): ?>
					<tr>
						<th scope="row"> <?= $i++ ?> </th>
						<td> <?= $price['article'] ?> </td>
						<td> <?= $price['product_name'] ?> </td>
						<td> <?= $price['supplier_name'] ?> </td>
						<td> <?= $price['warehouse_name'] ?> </td>
						<td> <?= $price['price'] ?> </td>
						<td> <?= $price['rrc'] ?> </td>
						<td> <?= $price['quantity'] ?> </td>
						<td> <?= $price['quantities'] ?> </td>
					</tr>
				<? endforeach; ?>
			<? endif; ?>
			</tbody>
		</table>
	<? endif; ?>
</div>
<br>
