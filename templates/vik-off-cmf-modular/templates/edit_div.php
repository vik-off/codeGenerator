
<ul id="submit-box-floating"></ul>

<h2><?= '<?= $this->pageTitle; ?>'; ?></h2>

<form id="edit-form" action="" method="post">
	<?= '<?= FORMCODE; ?>'; ?>
	
	<input type="hidden" name="id" value="<?= '<?= $this->instanceId; ?>'; ?>" />

<?
foreach($FIELDS_TITLES as $field => $title){
	if(!empty($ALLOWED_FIELDS[$field]) && $field != 'id'){
		echo
'	<div class="paragraph">
		<label class="title">'.$title.'</label>
		'.$this->getEditTplInput($INPUT_TYPES[$field], $field).'
	</div>
';	}
}
?>

	<div class="paragraph" id="submit-box">
		<input id="submit-save" class="button" type="submit" name="action[admin/<?=$MODULE;?>/save][admin/<?=$ADMIN_SECTION;?>/<?=$MODULE;?>/list]" value="Сохранить" title="Созхранить изменения и вернуться к списку" />
		<input id="submit-apply" class="button" type="submit" name="action[admin/<?=$MODULE;?>/save][admin/<?=$ADMIN_SECTION;?>/<?=$MODULE;?>/edit/<?= '<?= $this->instanceId ? $this->instanceId : \'(%id%)\' ; ?>'; ?>]" value="Применить" title="Сохранить изменения и продолжить редактирование" />
		<a id="submit-cancel" class="button" href="<?= "<?= href('admin/$ADMIN_SECTION/$MODULE/list'); ?>"; ?>" title="Отменить все изменения и вернуться к списку">отмена</a>
		<?= '<? if($this->instanceId): ?>'; ?>
		
			<a id="submit-delete" class="button" href="<?= "<?= href('admin/$ADMIN_SECTION/$MODULE/delete/'.\$this->instanceId); ?>"; ?>" title="Удалить запись">удалить</a>
			<a id="submit-copy" class="button" href="<?= "<?= href('admin/$ADMIN_SECTION/$MODULE/copy/'.\$this->instanceId); ?>"; ?>" title="Сделать копию записи">копировать</a>
		<?= '<? endif; ?>'; ?>
		
	</div>
</form>

<script type="text/javascript">

$(function(){
	$("#edit-form").validate( { <?= '<?= $this->validation; ?>'; ?> } );
	enableFloatingSubmits();
});

</script>
