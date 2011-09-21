
<ul id="submit-box-floating"></ul>

<h2>{$pageTitle}</h2>

<form id="edit-form" action="" method="post">
	{$formcode}
	<input type="hidden" name="id" value="{$instanceId}" />

	<div class="paragraph">
		<label class="title">index</label>
		<input type="text" name="index" value="{$index}" />
	</div>
	<div class="paragraph">
		<label class="title">Заголовок</label>
		<textarea name="title">{$title}</textarea>
	</div>
	<div class="paragraph" id="submit-box">
		<input id="submit-save" class="button" type="submit" name="action[jack-item/save][admin/content/jack-item/list]" value="Сохранить" title="Созхранить изменения и вернуться к списку" />
		<input id="submit-apply" class="button" type="submit" name="action[jack-item/save][admin/content/jack-item/edit/{if $instanceId}{$instanceId}{else}(%id%){/if}]" value="Применить" title="Сохранить изменения и продолжить редактирование" />
		<a id="submit-cancel" class="button" href="{a href=admin/content/jack-item/list}" title="Отменить все изменения и вернуться к списку">отмена</a>
		{if $instanceId}
		<a id="submit-delete" class="button" href="{a href=admin/content/jack-item/delete/$instanceId}" title="Удалить запись">удалить</a>
		{/if}
		{if $instanceId}
		<a id="submit-copy" class="button" href="{a href=admin/content/jack-item/copy/$instanceId}" title="Сделать копию записи">копировать</a>
		{/if}
	</div>
</form>

<script type="text/javascript">

$(function(){
	$("#edit-form").validate( { {{$validation}} } );
	enableFloatingSubmits();
});

</script>
