
{$pagination}

{if $collection}
	<table class="dataGrid">
	<tr>
		<th>id</th>
		<th>index</th>
		<th>Заголовок</th>
		<th>опции</th>
	</tr>
	{foreach from=$collection item='item'}
	<tr>
		<td>{$item.id}</td>
		<td>{$item.index}</td>
		<td>{$item.title}</td>
		<td style="font-size: 11px;">
			{a href=jack-item/view/`$item.id` text="Подробней"}
		</td>
	</tr>
	{/foreach}
	</table>
{else}
	<p>Сохраненных записей пока нет.</p>
{/if}

{$pagination}
