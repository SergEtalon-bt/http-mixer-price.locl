<div class="container-fluid">
	<div class="row m-5">
		<div class="col-4">
			<h2>Поставщики</h2>
		</div>
	</div>
	<div class="row m-2">
		<? if (isset($arData['suppliers'])): ?>
			<? foreach ($arData['suppliers'] as $supplier): ?>
				<div class="col-3">
					<div class="card bg-success" style="width: 18rem;">
						<img src="" class="card-img-top" alt="">
						<div class="card-body">
							<h5 class="card-title"><?= $supplier['name']; ?></h5>
							<? if (!empty($supplier['data_load_price']) && $supplier['data_load_price'] != '0000-00-00 00:00:00'): ?>
								<p class="card-text">Дата последней загрузки
									прайса: <?= $supplier['data_load_price']; ?></p>
							<? else: ?>
								<p class="card-text">Прайсы не загружались.</p>
							<? endif; ?>
							<p class="card-text"></p>
							<a href="/admin/suppliers/?action=load&id=<?= $supplier['id']; ?>" class="btn btn-primary">Загрузить
								прайс</a>
                            <br>
                            <br>
                            <a href="/admin/suppliers/?action=settings&id=<?= $supplier['id']; ?>" class="btn btn-primary">Настройка</a>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		<? endif; ?>
	</div>
</div>
