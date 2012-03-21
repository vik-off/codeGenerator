
<?= '<?= $this->pagination; ?>'; ?>


<?= '<? if($this->collection): ?>'; ?>

	<table>
	<tr>
<? foreach($FIELDS_TITLES as $field => $title)
	if(!empty($ALLOWED_FIELDS[$field]))
		echo "\t\t".'<th>'.$title.'</th>'."\r\n"; ?>
		
		<th>опции</th>
	</tr>
	<?= '<? foreach($this->collection as $item): ?>' ?>
	
	<tr>
<? foreach($FIELDS_TITLES as $field => $title)
	if(!empty($ALLOWED_FIELDS[$field]))
		echo "\t\t<td><?= \$item['$field']; ?></td>\r\n"; ?>
		
		<td style="font-size: 11px;">
			<a href="<?= "<?= href('$MODEL_NAME_LOW/view/'.\$item['id']); ?>"; ?>">Подробней</a>
		</td>
	</tr>
	<?= '<? endforeach; ?>'; ?>
	
	</table>
	
<?= '<? else: ?>'; ?>

	<p>Сохраненных записей пока нет.</p>
<?= '<? endif; ?>'; ?>


<?= '<?= $this->pagination; ?>'; ?>
