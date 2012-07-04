
<?= $this->pagination; ?>

<? if($this->collection): ?>
	<? foreach($this->collection as $item): ?>	
	<p>
		<h3>id</h3>
		<?= $item['id']; ?>
		<h3>title</h3>
		<?= $item['title']; ?>
		<h3>level</h3>
		<?= $item['level']; ?>
		<h3>flag</h3>
		<?= $item['flag']; ?>
		<h3>data</h3>
		<?= $item['data']; ?>
		<div><a href="<?= href('ololo/view/'.$item['id']); ?>">Подробней</a></div>
	</p>
	<? endforeach; ?>	
<? else: ?>
	<p>Сохраненных записей пока нет.</p>
<? endif; ?>
<?= $this->pagination; ?>
