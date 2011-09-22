
<?= $this->pagination; ?>

<? if($this->collection): ?>
	<table>
	<tr>
		<th>id</th>
		<th>index</th>
		<th>Заголовок</th>
		
		<th>опции</th>
	</tr>
	<? foreach($this->collection as $item): ?>	
	<tr>
		<td><?= $item['id']; ?></td>
		<td><?= $item['index']; ?></td>
		<td><?= $item['title']; ?></td>
		
		<td style="font-size: 11px;">
			<a href="<?= href('jack-item/view/'.$item['id']); ?>">Подробней</a>
		</td>
	</tr>
	<? endforeach; ?>	
	</table>
	
<? else: ?>
	<p>Сохраненных записей пока нет.</p>
<? endif; ?>

<?= $this->pagination; ?>