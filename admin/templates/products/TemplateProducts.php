<div class="container-fluid">
    <div class="row">
        <div class="col-5">
            <h1>Products</h1>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Код</th>
            <th scope="col">Название</th>
        </tr>
        </thead>
        <tbody>

        <? if (isset($arData['products'])): ?>
		<? $i = 1; ?>
            <? foreach ($arData['products'] as $product): ?>
                <tr>
                    <th scope="row"> <?= $i++ ?> </th>
                    <th scope="row"> <?= $product['kod'] ?> </th>
                    <td> <?= $product['name'] ?> </td>

                </tr>
            <? endforeach; ?>
        <? endif; ?>
        </tbody>
    </table>
</div>
<br>
