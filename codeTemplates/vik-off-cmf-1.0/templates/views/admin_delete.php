
<div style="text-align: center;">

	<div class="paragraph">

		Хотите удалить запись #<?= '<?= $this->instanceId; ?>'; ?>
		

<? foreach($FIELDS_TITLES as $field => $title)
	echo "\t\t".$title.': <?= $this->'.$field."; ?>, \n"; ?>
		
		безвозвратно?

	</div>
	
	<div class="paragraph">
		<form action="<?= "<?= href('admin/$ADMIN_SECTION'); ?>"; ?>" method="post">
			<input type="hidden" name="id" value="<?= '<?= $this->instanceId; ?>'; ?>" />
			<?= '<?= FORMCODE; ?>'; ?>
			
			<input class="button" type="submit" name="action[admin/<?=$MODULE;?>/delete]" value="Удалить" />
			<a class="button" href="<?= "<?= href('admin/$ADMIN_SECTION'); ?>"; ?>">Отмена</a>
		</form>
	</div>
	
</div>
