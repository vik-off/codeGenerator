
<div style="text-align: center; margin-top: 300px;">
	<h1 style="margin: 0 0 -5px; padding: 0; font-size: 30px; line-height: 50px; text-shadow:
	3px 2px 1px #DDD;">
		ШАБЛОНЫ ГЕНЕРАЦИИ
	</h1>
	<ul style="display: inline-block; text-align: left; line-height: 25px;">
		<? foreach ($this->templates as $t): ?>
			<li><a href="<?= href('template/'.$t); ?>"><?= $t; ?></a></li>
		<? endforeach; ?>
	</ul>
</div>