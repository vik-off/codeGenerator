
<?= '<?= $this->pagination; ?>'; ?>


<?= '<? if($this->collection): ?>'; ?>

	<?= '<? foreach($this->collection as $item): ?>' ?>
	
	<p>
<? foreach($FIELDS_TITLES as $field => $title)
	if(!empty($ALLOWED_FIELDS[$field]))
		echo "\t\t".'<h3>'.$title.'</h3>'."\r\n"
			."\t\t<?= \$item['$field']; ?>\r\n"; ?>
		<div><a href="<?= "<?= href('$MODULE/view/'.\$item['id']); ?>"; ?>">Подробней</a></div>
	</p>
	<?= '<? endforeach; ?>'; ?>
	
<?= '<? else: ?>'; ?>

	<p>Сохраненных записей пока нет.</p>
<?= '<? endif; ?>'; ?>

<?= '<?= $this->pagination; ?>'; ?>

