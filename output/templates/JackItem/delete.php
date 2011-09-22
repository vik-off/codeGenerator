
<div style="text-align: center;">

	<div class="paragraph">

		Хотите удалить запись #<?= $this->instanceId; ?>		

		id: <?= $this->id; ?>, 
		index: <?= $this->index; ?>, 
		Заголовок: <?= $this->title; ?>, 
		
		безвозвратно?

	</div>
	
	<div class="paragraph">
		<form action="" method="post">
			<input type="hidden" name="id" value="<?= $this->instanceId; ?>" />
			<?= FORMCODE; ?>			
			<input class="button" type="submit" name="action[jack-item/delete]" value="Удалить" />
			<a class="button" href="<?= href('admin/content/jack-item'); ?>">Отмена</a>
		</form>
	</div>
	
</div>
