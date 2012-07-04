
<div class="options-row">
	<a href="<?= href('admin/content/ololo/new'); ?>">Добавить запись</a>
</div>

<? if($this->collection): ?>

	<?= $this->pagination; ?>

	<table class="grid wide tr-highlight">
	<tr>
		<th><?= $this->sorters['id']; ?></th>
		<th><?= $this->sorters['title']; ?></th>
		<th><?= $this->sorters['level']; ?></th>
		<th><?= $this->sorters['flag']; ?></th>
		<th><?= $this->sorters['data']; ?></th>
		<th>Опции</th>
	</tr>
	<? foreach($this->collection as $item): ?>	
	<tr>
		<td><?= $item['id']; ?></td>
		<td><?= $item['title']; ?></td>
		<td><?= $item['level']; ?></td>
		<td><?= $item['flag']; ?></td>
		<td><?= $item['data']; ?></td>
			
		<td class="center" style="width: 90px;">
			<div class="tr-hover-visible options">
				<a href="<?= href('ololo/view/'.$item['id']); ?>" title="Просмотреть"><img src="images/backend/icon-view.png" alt="Просмотреть" /></a>
				<a href="<?= href('admin/content/ololo/edit/'.$item['id']); ?>" title="Редактировать"><img src="images/backend/icon-edit.png" alt="Редактировать" /></a>
				<a href="<?= href('admin/content/ololo/delete/'.$item['id']); ?>" title="Удалить"><img src="images/backend/icon-delete.png" alt="Удалить" /></a>
			</div>
		</td>
	</tr>
	<? endforeach; ?>	
	</table>

	<?= $this->pagination; ?>	
	
<? else: ?>

	<p>Сохраненных записей пока нет.</p>
	
<? endif; ?>
