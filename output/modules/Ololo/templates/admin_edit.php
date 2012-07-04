
<ul id="submit-box-floating"></ul>

<h2><?= $this->pageTitle; ?></h2>

<form id="edit-form" action="" method="post">
	<?= FORMCODE; ?>	
	<input type="hidden" name="id" value="<?= $this->instanceId; ?>" />

	<div class="paragraph">
		<label class="title">title</label>
		<?= Html_Form::inputText(array('name' => 'title', 'value' => $this->title)); ?>
	</div>
	<div class="paragraph">
		<label class="title">level</label>
		<?= Html_Form::inputText(array('name' => 'level', 'value' => $this->level)); ?>
	</div>
	<div class="paragraph">
		<label class="title">flag</label>
		<?= Html_Form::inputText(array('name' => 'flag', 'value' => $this->flag)); ?>
	</div>
	<div class="paragraph">
		<label class="title">data</label>
		<?= Html_Form::inputText(array('name' => 'data', 'value' => $this->data)); ?>
	</div>

	<div class="paragraph" id="submit-box">
		<input id="submit-save" class="button" type="submit" name="action[admin/ololo/save][admin/content/ololo/list]" value="Сохранить" title="Созхранить изменения и вернуться к списку" />
		<input id="submit-apply" class="button" type="submit" name="action[admin/ololo/save][admin/content/ololo/edit/<?= $this->instanceId ? $this->instanceId : '(%id%)' ; ?>]" value="Применить" title="Сохранить изменения и продолжить редактирование" />
		<a id="submit-cancel" class="button" href="<?= href('admin/content/ololo/list'); ?>" title="Отменить все изменения и вернуться к списку">отмена</a>
		<? if($this->instanceId): ?>		
			<a id="submit-delete" class="button" href="<?= href('admin/content/ololo/delete/'.$this->instanceId); ?>" title="Удалить запись">удалить</a>
			<a id="submit-copy" class="button" href="<?= href('admin/content/ololo/copy/'.$this->instanceId); ?>" title="Сделать копию записи">копировать</a>
		<? endif; ?>		
	</div>
</form>

<script type="text/javascript">

$(function(){
	$("#edit-form").validate( { <?= $this->validation; ?> } );
	enableFloatingSubmits();
});

</script>
