<div class="container-fluid">
    <div class="row m-5">
        <div class="col-4">
            <h2>Поставщик</h2>
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
    <div class="row m-5">
        <div class="col-6">
            <form action="/admin/suppliers/?action=load&id=<?= $arData['supplier']['id']; ?>" method="post"
                  enctype="multipart/form-data" id="form_upload">
                <div class="input-group mb-3">
                    <input type="hidden" name="MAX_FILE_SIZE" value="30000000"/>
                    <input type="file" class="form-control" id="price_file" name="price_file">
                    <? if (isset($arData['select_price_file'])): ?>
                        <label class="input-group-text" for="price_file"><?= $arData['select_price_file']; ?></label>
                    <? endif; ?>
                </div>
                <? if (isset($arData['price_msg'])): ?>
                    <p><?= $arData['price_msg']; ?></p>
                <? endif; ?>
                <? if (isset($arData['price_error'])): ?>
                    <p><?= $arData['price_error']; ?></p>
                <? endif; ?>
                <button type="submit" class="btn btn-primary" value="load" name="form_upload">Загрузить прайс</button>
                <? if (isset($arData['select_process'])): ?>
                    <!--				<label class="" for="sklad">--><?php //= $arData['select_process']['name']; ?><!--</label>-->
                    <h3><?= $arData['select_process']['name']; ?></h3>
                    <select id="sklad" class="form-select" name="<?= $arData['select_process']['option_name']; ?>">
                        <? foreach ($arData['select_process']['options'] as $option): ?>
                            <option value="<?= $option['value']; ?>"><?= $option['name']; ?></option>
                        <? endforeach; ?>
                    </select>
                    <br>
                <? endif; ?>
                <? if (isset($arData['button_process'])): ?>
                    <button type="submit" class="btn btn-primary" value="process"
                            name="<?= $arData['button_process']['post_name'] ?>"><?= $arData['button_process']['name'] ?>
                    </button>
                <? endif; ?>
                <? if (isset($arData['price_processed'])): ?>
                    <p><?= $arData['price_processed']; ?></p>
                <? endif; ?>
                <? if (!empty($arData['missing_products'])): ?>
                    <pre>
                    <? var_export($arData['missing_products']); ?>
                </pre>
                <? endif; ?>
            </form>
        </div>
    </div>
</div>
