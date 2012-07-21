
<div class="options-row">
	<a href="<?= "<?= href('admin/$ADMIN_SECTION/new'); ?>"; ?>">Добавить запись</a>
</div>

<?= '<? if($this->collection): ?>'; ?>


	<?= '<?= $this->pagination; ?>'; ?>


	<table class="grid wide tr-highlight">
	<tr>
<? foreach($FIELDS_TITLES as $field => $title){
	if(!empty($ALLOWED_FIELDS[$field]))
		echo "\t\t".'<th>'.(!empty($SORTABLE_FIELDS[$field]) ? "<?= \$this->sorters['".$field."']; ?>" : $title).'</th>'."\n";
	} ?>
		<th>Опции</th>
	</tr>
	<?= '<? foreach($this->collection as $item): ?>' ?>
	
	<?= !empty($BLOCKS['PUBLISH'])
		? '<tr <?= !$item[\'published\'] ? \'class="unpublished"\' : \'\'; ?>>'
		: '<tr>'; ?>

<?
foreach($FIELDS_TITLES as $field => $title) {
	if(!empty($ALLOWED_FIELDS[$field])) {
		if ($field === 'published' && !empty($BLOCKS['PUBLISH'])) {
			echo
'		<td class="publish-cell">
		
			<div class="tr-hover-opened" style="height: 18px;">
				<form class="inline" action="" method="post">
					<input type="hidden" name="id" value="<?= $item[\'id\']; ?>" />
					<?= FORMCODE; ?>
					<? if($item[\'published\']): ?>
						<input class="button-small" type="submit" name="action[admin/'.$MODULE.'/unpublish]" value="Скрыть" />
					<? else: ?>
						<input class="button-small" type="submit" name="action[admin/'.$MODULE.'/publish]" value="Опубликовать" />
					<? endif; ?>
				</form>
			</div>
			
			<div class="tr-hover-closed">
				<? if($item[\'published\']): ?> Опубл. <? else: ?> Скрыт <? endif; ?>
			</div>
		</td>
';
			continue;
		}
		echo "\t\t<td><?= \$item['".$field."']; ?></td>\n";
	}
}
?>
			
		<td class="center" style="width: 90px;">
			<div class="tr-hover-visible options">
				<a href="<?= "<?= href('$MODULE/view/'.\$item['id']); ?>"; ?>" title="Просмотреть"><img src="images/backend/icon-view.png" alt="Просмотреть" /></a>
				<a href="<?= "<?= href('admin/$ADMIN_SECTION/edit/'.\$item['id']); ?>"; ?>" title="Редактировать"><img src="images/backend/icon-edit.png" alt="Редактировать" /></a>
				<a href="<?= "<?= href('admin/$ADMIN_SECTION/delete/'.\$item['id']); ?>"; ?>" title="Удалить"><img src="images/backend/icon-delete.png" alt="Удалить" /></a>
			</div>
		</td>
	</tr>
	<?= '<? endforeach; ?>'; ?>
	
	</table>

	<?= '<?= $this->pagination; ?>'; ?>
	
	
<?= '<? else: ?>'; ?>


	<p>Сохраненных записей пока нет.</p>
	
<?= '<? endif; ?>'; ?>

