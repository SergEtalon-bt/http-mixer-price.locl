<? if (isset($arData['left_menu'])): ?>
	<div class="col-10">
		<? foreach ($arData['left_menu'] as $item): ?>
			<a href="<?= $item['href']; ?>"><button type="button" class="btn btn-success mx-3"><?=$item['name'];?></button></a>
		<? endforeach; ?>
	</div>
<? endif; ?>
