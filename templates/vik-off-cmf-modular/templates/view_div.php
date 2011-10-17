
<div><a href="<?= "<?= href('$MODULE/list'); ?> "; ?>">Вернуться к списку</a></div>

<h2>Запись #<?= '<?= $this->instanceId; ?>'; ?></h2>

<?
foreach($FIELDS_TITLES as $field => $title)
	if(!empty($ALLOWED_FIELDS[$field]))
		echo
'<div class="paragraph">
	<h3>'.$title.'</h3>
	<?= $this->'.$field.'; ?>
</div>
';
?>
