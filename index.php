<?
session_start();
define('FS_ROOT', dirname(__FILE__).'/');
define('GEN_TYPE', 'all');
error_reporting(E_ALL & ~E_NOTICE);

require_once('setup.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
     "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Кодогенератор</title>
	
	<link rel="stylesheet" href="data/style.css" type="text/css" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://scripts.vik-off.net/debug.js"></script>
	<script type="text/javascript" src="http://scripts.vik-off.net/plugins/jquery.simpleCheckbox.js"></script>
</head>
<body>

<?

echo Messenger::get()->getAll();

require('data/actions.php');

?>

<script type="text/javascript">
	
	function ge(id){return document.getElementById(id);}
	
	function capitalize(string){return string.charAt(0).toUpperCase() + string.slice(1);}
	
	function tblNameEdit(){
		var table = ge('tablename').value.toLowerCase();
		ge('modulename').value = table;
		ge('modelclass').value = capitalize(table) + '_Model';
		ge('controlclass').value = capitalize(table) + '_Controller';
	}
	
	function moduleNameEdit(){
		var module = ge('modulename').value;
		ge('modelclass').value = capitalize(module) + '_Model';
		ge('controlclass').value = capitalize(module) + '_Controller';
	}
	
	document.body.onload = function(){
	
		ge('tablename').onkeyup = tblNameEdit;
		ge('modulename').onkeyup = moduleNameEdit;
	
		ge('modelclass').onkeyup = function(){
			ge('controlclass').value = this.value.replace('_Model', '_Controller');
		}
		
	}
	
	$(function(){
		$('input[type="checkbox"].pretty').simpleCheckbox();
	});
	
</script>

<p>
	<form align="right" action="" method="post" onsubmit="return confirm('Удалить все сохраненные данные?')">
		<input type="hidden" name="action" value="clearSession" />
		<input type="submit" name="clear-session" value="Очистить сессию" />
	</form>
</p>

<form method="post">
<input type="hidden" name="action" value="saveData" />

	<table>
	<tr>
		<td>Структура БД</td>
		<td>
			<?
			if(count(getVar($s['tableStruct'], array(), 'array'))){
				$fields = array();
				foreach($s['tableStruct'] as $f)
					$fields[] = $f['Field'].' [ '.$f['Type'].' ]';
				echo implode(',<br />', $fields);
			}else{
				echo "<a href=\"#\" onclick=\"window.open('table-structure.php','validationRules','width=600,height=600,left=200,top=20'); return false;\">Получить</a>";
			}
			?>
		</td>
	</tr>
	<tr>
		<td>Таблица БД</td>
		<td>
			<input id="tablename" type="text" name="tablename" value="<?=getVar($s['tablename']);?>">
			<a href="#" onclick="tblNameEdit(); return false;">Раздать имена</a>
		</td>
	</tr><tr>
		<td>Модуль</td>
		<td><input id="modulename" type="text" name="modulename" value="<?=getVar($s['modulename']);?>"></td>
	</tr><tr>
		<td>Класс модели</td>
		<td><input id="modelclass" type="text" name="modelclass" value="<?=getVar($s['modelclass']);?>"></td>
	</tr><tr>
		<td>Класс контроллера</td>
		<td><input id="controlclass" type="text" name="controlclass" value="<?=getVar($s['controlclass']);?>"></td>
	</tr><tr>
		<td>Раздел адм. панели</td>
		<td><input type="text" name="admSection" value="<?=getVar($s['admSection'], 'content');?>"></td>
	</tr><tr>
		<td>Поля</td>
		<td>
			<? if(!empty($s['tableStruct'])): ?>
				<table border class="fields-list">
					<tr>
						<th rowspan="2">Поле</th>
						<th rowspan="2">Заголовок<br /><span class="normal">(*.tpl)</span></th>
						<th rowspan="2">Сортируемое<br /><span class="normal">(admin_list.tpl)</span></th>
						<th colspan="4">Шаблоны</th>
						<th rowspan="2">Тип<br /><span class="normal">(edit.tpl)</span></th>
						<th rowspan="2">___</th>
					</tr>
					<tr>
						<th>admin-list</th>
						<th>list</th>
						<th>view</th>
						<th>edit</th>
					</tr>
				<? foreach((array)$s['tableStruct'] as $row): ?>
					<? $f = $row['Field'];?>
					<tr>
						<td><?=$f;?></td>
						<td><input type="text" name="fieldsTitles[<?=$f;?>]" value="<?=isset($s['fieldsTitles'][$f]) ? $s['fieldsTitles'][$f] : $f;?>" /></td>
						<td><?=Inp::checkbox('sortableFields['.$f.']', !isset($s['sortableFields'][$f]) || $s['sortableFields'][$f], '', array('class' => 'pretty'));?></td>
						<td><?=Inp::checkbox('tplFields[admin-list]['.$f.']', !isset($s['tplFields']['admin-list'][$f]) || $s['tplFields']['admin-list'][$f], '', array('class' => 'pretty'));?></td>
						<td><?=Inp::checkbox('tplFields[list]['.$f.']', !isset($s['tplFields']['list'][$f]) || $s['tplFields']['list'][$f], '', array('class' => 'pretty'));?></td>
						<td><?=Inp::checkbox('tplFields[view]['.$f.']', !isset($s['tplFields']['view'][$f]) || $s['tplFields']['view'][$f], '', array('class' => 'pretty'));?></td>
						<td><?=Inp::checkbox('tplFields[edit]['.$f.']', !isset($s['tplFields']['edit'][$f]) || $s['tplFields']['edit'][$f], '', array('class' => 'pretty'));?></td>
						<td><?=Inp::select('inputTypes['.$f.']', Inp::$tplVarInputTypes, getVar($s['inputTypes'][$f]));?></td>
					</tr>
				<? endforeach; ?>
				</table>
			<? else: ?>
				-
			<? endif; ?>
		</td>
	</tr><tr>
		<td>Общие правила<br />валидации<br /></td>
		<td>
			<textarea name="validatCommonRules" style="width: 900px; height: 75px;"><?
				if(getVar($s['strValidatCommonRules'])){
					echo $s['strValidatCommonRules'];
				}elseif(getVar($s['validatCommonRules'])){
					echo DbStructParser::getArrStr($s['validatCommonRules'], "            ");
				}
			?></textarea>
		</td>
	</tr><tr>
		<td>Индивидуальные<br />правила валидации<br /></td>
		<td>
			<textarea name="validatIndividRules" style="width: 900px; height: 150px;"><?
				if(getVar($s['strValidatIndividRules'])){
					echo $s['strValidatIndividRules'];
				}elseif(getVar($s['validatIndividRules'])){
					echo DbStructParser::getArrStr($s['validatIndividRules'], "            ");
				}
			?></textarea>
		</td>
	</tr><tr>
		<td>Шаблон</td>
		<td>
			<select name="template">
				<option value="">Выберите...</option>
				<?
				foreach(file('./templates/templates.txt') as $t){
					$t = trim($t);
					echo '<option value="'.$t.'" '.(getVar($s['template']) == $t ? 'selected="selected"' : '').'>'.$t.'</option>';
				}
				?>
			</select>
		</td>
	</tr><tr>
		<td></td>
		<td><input type="submit" name="step1save" value="Сохранить"></td>
	</tr>
	</table>
	
</form>

<br />
<br />

<form action="" method="post">
	
	<input type="hidden" name="action" value="generate" />

	<table border="1" style="font-size: 12px; margin: auto;">
	<tr>
		<td colspan="3" align="center">
			<div class="<?=strlen($s['template']) ? 'green' : 'red'; ?>">Шаблон</div>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<b style="font-size: 16px;">Model</b>
			<div class="<?=!empty($s['modelclass']) ? 'green' : 'red'; ?>">Имя модели</div>
			<div class="<?=!empty($s['tablename']) ? 'green' : 'red'; ?>">Имя таблицы БД</div>
			<div class="<?=!empty($s['strValidatCommonRules']) && !empty($s['strValidatIndividRules']) ? 'green' : ''; ?>">Правила валидации</div>
		</td><td>
			<b style="font-size: 16px;">Controller</b><br />
			<div class="<?=strlen($s['controlclass']) > 10 ? 'green' : 'red'; ?>">Имя контроллера</div>
			<div class="<?=!empty($s['modelclass']) ? 'green' : 'red'; ?>">Имя модели</div>
		</td><td>
			<b style="font-size: 16px;">Templates</b><br />
			<div class="green">Заголовки полей</div>
		</td>
	</tr>
	<tr valign="top">
		<td>      <!-- МОДЕЛЬ -->
			<? if(!empty($s['modelclass']) && 
				  !empty($s['template']) &&
				  !empty($s['tablename'])): ?>
				<p><input id="model-generate" type="checkbox" name="files[model]" value="1" <?=(getVar($s['files']['model']) ? 'checked="checked"' : '');?> /> <label for="model-generate">Сгенерировать</label></p>
			<? endif; ?>
		</td><td> <!-- КОНТРОЛЛЕР -->
			<? if(strlen($s['controlclass']) > 10 && 
				  !empty($s['template']) &&
				  !empty($s['modelclass'])): ?>
				<p><input id="control-generate" type="checkbox" name="files[controller]" value="1" <?=(getVar($s['files']['controller']) ? 'checked="checked"' : '');?> /> <label for="control-generate">Сгенерировать</label></p>
			<? endif; ?>
		</td><td> <!-- ШАБЛОНЫ -->
		
			<? if(!empty($s['template'])): ?>
			<table style="font-size: 12px;">
				<tr><td>admin-list:</td><td><select name="files[tpl-admin-list]"><?=getHtmlTempateTypesList($s['files']['tpl-admin-list'], 'te');?></select></td></tr>
				<tr><td>list:</td><td><select name="files[tpl-list]"><?=getHtmlTempateTypesList(getVar($s['files']['tpl-list']));?></select></td></tr>
				<tr><td>view:</td><td><select name="files[tpl-view]"><?=getHtmlTempateTypesList(getVar($s['files']['tpl-view']));?></select></td></tr>
				<tr><td>edit:</td><td><select name="files[tpl-edit]"><?=getHtmlTempateTypesList(getVar($s['files']['tpl-edit']));?></select></td></tr>
				<tr><td>delete:</td><td><select name="files[tpl-delete]"><?=getHtmlTempateTypesList(getVar($s['files']['tpl-delete']), 'de');?></select></td></tr>
			</table>
			<? endif; ?>
			
		</td>
	</tr>
	
	<? if(getVar($s['template'])): ?>
	<tr>
		<td colspan="3" align="center">
			<input type="checkbox" id="clear-output-dir" name="clear-output-dir" value="1" <?=(getVar($s['clear-output-dir']) ? 'checked="checked"' : '');?> /> <label for="clear-output-dir">Очистить предыдущие</label><br />
			<input type="submit" name="" value="Сгенерировать" />
		</td>
	</tr>
	<? endif; ?>
	
	</table>
</form>

<br />
<br />

</body>
</html>

<?
echo'<pre>'; print_r($s);
?>