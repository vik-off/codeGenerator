<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?= $this->_getHtmlTitle(); ?></title>
	<base href="<?= $this->_getHtmlBaseHref(); ?>" />

	<link rel="stylesheet" type="text/css" href="style.css" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://scripts.vik-off.net/debug.js"></script>
	<script type="text/javascript">
	
		function href(href){
			return 'index.php?r=' + href;
		}
		(function($){
			$.fn.simpleCheckbox = function(o){
				o = $.extend({
					'hide': false,
					'class': 'checked',
				}, o);
				this.each(function(){
					if(!this.labels.length) return;
					var t = $(this);
					if(o.hide) t.css('display', 'none');
					var labels = $([]);
					for(var i = 0; i < this.labels.length; i++)
						labels = labels.add(this.labels[i]);
					t.data('simpleCheckbox', {labels: labels, class: o.class});
					t.change(updateLabels);
					updateLabels.call(this);
				});
				function updateLabels(){
					var t = $(this);
					var d = t.data('simpleCheckbox');
					var method = t.attr('checked') ? 'addClass' : 'removeClass';
					d.labels[method](d.class);
				}
				return this;
			};
		})(jQuery);
		
		$(function(){
			
			VikDebug.init();
			
			// отлов ajax-ошибок
			$.ajaxSetup({
				error: function(xhr){
					trace(xhr.responseText);
					return true;
				}
			});
		});
	
	</script>
	<style type="text/css">
	body {
		font-family: verdana, tahoma, sans-serif;
	}
	a{
		text-decoration: none;
	}
	a:hover{
		text-decoration: underline;
	</style>
</head>
<body>

	<?= Messenger::get()->getAll(); ?>

	<?= $this->_getHtmlContent(); ?>
	
</body>
</html>
