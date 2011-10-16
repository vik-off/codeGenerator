
<div style="text-align: center;">

	<div class="paragraph">

		Хотите удалить запись #<?= '<?= $this->instanceId; ?>'; ?>
		

<? foreach($FIELDS_TITLES as $field => $title)
	echo "\t\t".$title.': <?= $this->'.$field."; ?>, \n"; ?>
		
		безвозвратно?

	</div>
	
	<div class="paragraph">
		<form action="" method="post">
			<input type="hidden" name="id" value="<?= '<?= $this->instanceId; ?>'; ?>" />
			<?= '<?= FORMCODE; ?>'; ?>
			
			<input class="button" type="submit" name="action[<?=$MODEL_NAME_LOW;?>/delete]" value="Удалить" />
			<a class="button" href="<?= "<?= href('admin/$ADMIN_SECTION/$MODEL_NAME_LOW'); ?>"; ?>">Отмена</a>
		</form>
	</div>
	
</div>
