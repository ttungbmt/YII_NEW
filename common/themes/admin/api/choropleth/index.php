<div class="m-2">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>STT</th>
            <th>Tên <?=$isQuan ? 'Phường/Xã' : 'Quận/Huyện' ?></th>
            <th>Số lượng</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $k => $item):?>
        <tr>
            <td class="tableexport-number"><?=$k+1?></td>
            <td class="tableexport-string"><?=data_get($item, 'label')?></td>
            <td class="tableexport-number"><?=data_get($item, 'count')?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


