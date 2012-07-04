
<div style="text-align: center;">

	<div class="paragraph">

		Хотите удалить запись #<?= $this->instanceId; ?>		

		id: <?= $this->id; ?>, 
		title: <?= $this->title; ?>, 
		level: <?= $this->level; ?>, 
		flag: <?= $this->flag; ?>, 
		data: <?= $this->data; ?>, 
		
		безвозвратно?

	</div>
	
	<div class="paragraph">
		<form action="<?= href('admin/content/ololo'); ?>" method="post">
			<input type="hidden" name="id" value="<?= $this->instanceId; ?>" />
			<?= FORMCODE; ?>			
			<input class="button" type="submit" name="action[admin/ololo/delete]" value="Удалить" />
			<a class="button" href="<?= href('admin/content/ololo'); ?>">Отмена</a>
		</form>
	</div>
	
</div>
