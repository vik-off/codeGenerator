
<div><a href="<?= href('ololo/list'); ?> ">Вернуться к списку</a></div>

<h2>Запись #<?= $this->instanceId; ?></h2>

<table>
<tr>
	<td class="title">id</td>
	<td class="data"><?= $this->id; ?></td>
</tr>
<tr>
	<td class="title">title</td>
	<td class="data"><?= $this->title; ?></td>
</tr>
<tr>
	<td class="title">level</td>
	<td class="data"><?= $this->level; ?></td>
</tr>
<tr>
	<td class="title">flag</td>
	<td class="data"><?= $this->flag; ?></td>
</tr>
<tr>
	<td class="title">data</td>
	<td class="data"><?= $this->data; ?></td>
</tr>
</table>
