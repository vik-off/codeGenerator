
<h2 align="center">Структура таблицы БД</h2>

<form action="" method="post">
	Строка CREATE TABLE<br />
	<textarea name="structure" style="width: 95%; height: 350px;"><?= $this->structure_str; ?></textarea><br />
	<input type="submit" name="" value="Обработать" />

</form>

<?php if ($this->structure_arr) { ?>
	<pre><?php print_r($this->structure_arr); ?></pre>
<?php } ?>