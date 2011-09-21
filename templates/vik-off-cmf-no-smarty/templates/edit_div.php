
<ul id="submit-box-floating"></ul>

<h2>{$pageTitle}</h2>

<form id="edit-form" action="" method="post">
	{$formcode}
	<input type="hidden" name="id" value="{$instanceId}" />

<?
foreach($FIELDS_TITLES as $field => $title){
	if(!empty($ALLOWED_FIELDS[$field]) && $field != 'id'){
		echo
'	<div class="paragraph">
		<label class="title">'.$title.'</label>
		'.Inp::getEditTplInput($INPUT_TYPES[$field], $field).'
	</div>
';	}
}
?>
	<div class="paragraph" id="submit-box">
		<input id="submit-save" class="button" type="submit" name="action[<?=$MODEL_NAME_LOW;?>/save][admin/content/<?=$MODEL_NAME_LOW;?>/list]" value="Сохранить" title="Созхранить изменения и вернуться к списку" />
		<input id="submit-apply" class="button" type="submit" name="action[<?=$MODEL_NAME_LOW;?>/save][admin/content/<?=$MODEL_NAME_LOW;?>/edit/{if $instanceId}{$instanceId}{else}(%id%){/if}]" value="Применить" title="Сохранить изменения и продолжить редактирование" />
		<a id="submit-cancel" class="button" href="{a href=admin/content/<?=$MODEL_NAME_LOW;?>/list}" title="Отменить все изменения и вернуться к списку">отмена</a>
		{if $instanceId}
		<a id="submit-delete" class="button" href="{a href=admin/content/<?=$MODEL_NAME_LOW;?>/delete/$instanceId}" title="Удалить запись">удалить</a>
		{/if}
		{if $instanceId}
		<a id="submit-copy" class="button" href="{a href=admin/content/<?=$MODEL_NAME_LOW;?>/copy/$instanceId}" title="Сделать копию записи">копировать</a>
		{/if}
	</div>
</form>

<script type="text/javascript">

$(function(){
	$("#edit-form").validate( { {{$validation}} } );
	enableFloatingSubmits();
});

</script>
