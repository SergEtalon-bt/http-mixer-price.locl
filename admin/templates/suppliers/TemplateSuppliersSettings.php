<div class="container-fluid">
	<div class="row m-5">
		<div class="col-4">
			<h2>Настройка</h2>
		</div>
	</div>
    <div class="row m-2">
        <? if (isset($arData['supplier'])): ?>
            <!--		--><? // var_export($arData['supplier']); ?>
            <div class="col-3">
                <div class="card bg-success" style="width: 18rem;">
                    <img src="" class="card-img-top" alt="">
                    <div class="card-body">
                        <h5 class="card-title"><?= $arData['supplier']['name']; ?></h5>
                        <? if (!empty($arData['supplier']['data_load_price']) && $arData['supplier']['data_load_price'] != '0000-00-00 00:00:00'): ?>
                            <p class="card-text">Дата последней загрузки
                                прайса: <?= $arData['supplier']['data_load_price']; ?></p>
                        <? else: ?>
                            <p class="card-text">Прайсы не загружались.</p>
                        <? endif; ?>
                        <p class="card-text"></p>
                    </div>
                </div>
            </div>
        <? endif; ?>
    </div>
</div>
