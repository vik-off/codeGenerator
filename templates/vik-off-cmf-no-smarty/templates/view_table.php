
<div><a href="<?= "<?= href('$MODEL_NAME_LOW/list'); ?> "; ?>">Вернуться к списку</a></div>

<h2>Запись #<?= '<?= $this->instanceId; ?>'; ?></h2>

<table>
<?
foreach($FIELDS_TITLES as $field => $title)
	if(!empty($ALLOWED_FIELDS[$field]))
		echo
'<tr>
	<td class="title">'.$title.'</td>
	<td class="data"><?= $this->'.$field.'; ?></td>
</tr>
';
?>
</table>
