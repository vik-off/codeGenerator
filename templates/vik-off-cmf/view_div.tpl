
<div>{a href=<?=$MODEL_NAME_LOW;?>/list text="Вернуться к списку"}</div>

<h2>Запись #{$instanceId}</h2>

<?
foreach($FIELDS_TITLES as $field => $title)
	if(!empty($ALLOWED_FIELDS[$field]))
		echo
'<p>
	<h3>'.$title.'</h3>
	{$'.$field.'}
</p>
';
?>
