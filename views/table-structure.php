<style type="text/css">
.menu a{
	font-size: 13px;
	color: blue;
	font-weight: bold;
	text-decoration: none;
}
.menu a:hover{
	text-decoration: underline;
}
.menu a.active{
	text-decoration: underline;
}
</style>

<?

$action = isset($_GET['action']) ? $_GET['action'] : 'db-parse-create';

?>


<h2 align="center">Структура таблицы БД</h2>

<div class="menu" align="center">
	<a <?=$action == 'db-parse-create' ? 'class="active"' : '';?> href="?action=db-parse-create">Парсинг CREATE TABLE строки</a> |
	<a <?=$action == 'db-eval-describe' ? 'class="active"' : '';?> href="?action=db-eval-describe">Парсинг DESCRIBE массива</a>
</div>
<br />

<? if($action =='db-parse-create'){ ?>

	<form action="" method="post">
		<?= FORMCODE; ?>
		<input type="hidden" name="action" value="parse-db-structure" />
		<input type="hidden" name="template" value="<?= $this->template; ?>" />
		<input type="hidden" name="input-type" value="create" />
		Строка CREATE TABLE<br />
		<textarea name="structure" style="width: 95%; height: 350px;"></textarea><br />
		<input type="submit" name="" value="Обработать" />
		
	</form>
	
<? }elseif($action == 'db-eval-describe'){ ?>

	<form action="" method="post">
		<?= FORMCODE; ?>
		<input type="hidden" name="action" value="parse-db-structure" />
		<input type="hidden" name="template" value="<?= $this->template; ?>" />
		<input type="hidden" name="input-type" value="describe" />
		Валидный PHP массив, полученный от DESCRIBE<br />
		<textarea name="structure" style="width: 100%; height: 350px;"
			><? var_export(getVar(Storage::get($this->template)->data['tableStruct']));?></textarea>
		<br />
		<input type="submit" name="" value="Обработать" />
		
	</form>
	
<? } ?>
