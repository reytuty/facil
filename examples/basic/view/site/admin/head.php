
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>Painel Administrativo</title>
		<link rel="stylesheet" media="screen" href="<?php echo Config::getAsset('backend/assets/css/style.css'); ?>" />
		<!--[if IE 9]>
		    <link rel="stylesheet" media="screen" href="<?php echo Config::getAsset('backend/assets/css/ie9.css'); ?>"/>
		<![endif]-->
		
		<!--[if IE 8]>
		    <link rel="stylesheet" media="screen" href="<?php echo Config::getAsset('backend/assets/css/ie8.css'); ?>"/>
		<![endif]-->
		
		<!--[if IE 7]>
		    <link rel="stylesheet" media="screen" href="<?php echo Config::getAsset('backend/assets/css/ie7.css'); ?>"/>
		<![endif]-->
		
		<script type="text/javascript" src="<?php echo Config::getRootPath('assets/js/config/inicial.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery-1.7.min.js'); ?>"></script>		
						
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/custom/general.js'); ?>"></script>		
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/custom/gallery.js');?>"></script>
		<!-- Daqui para baixo está certo -->
				
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery-1.7.min.js'); ?>"></script>		
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery.validate.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery.colorbox-min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery.flot.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery.dataTables.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/jquery-ui-1.8.16.custom.min.js'); ?>"></script>
		

		<!--Fancybox -->
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/fancybox/jquery.mousewheel-3.0.4.pack.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/fancybox/jquery.fancybox-1.3.4.pack.js'); ?>"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo Config::getAsset('backend/assets/js/plugins/fancybox/jquery.fancybox-1.3.4.css'); ?>" media="screen" />
		<script>
			$(document).ready(function() {
				$("#upload").fancybox({
					'width'				: 800,
					'height'			: 500,
					'autoScale'			: false,
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'type'				: 'iframe',
					'onClosed'			: function(){window.location.href=window.location.href;}
				});	
				$("#changeDescription").fancybox({
					'width'				: 800,
					'height'			: 500,
					'autoScale'			: false,
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'type'				: 'iframe',
					'onClosed'			: function(){window.location.href=window.location.href;}
				});				
				$(".ui-sortable").sortable({
				helper: function(e, tr){
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each(function(index){
				$(this).width($originals.eq(index).width());
				$(this).addClass("onrelease");
				});
				return $helper;
				},
				update: function() {
				var i = 0; array_id = [], order = [];
				$("[id^=item]").each(function(){
				array_id[i] = $(this).attr("id").replace("item","");
				i++;
				});
				$.post('<?php echo Config::getRootPath() ?>' + "backend/order_change/link/",{table:'content',array_content_id:array_id,category_id:$("#category_id").val(),linked_table:$("#linked_table").val()},function(r){
				if(!r.success){
				alert("Houve um erro na tentativa de ordernar os itens!<br /><br />" + r.message);
				}
				},"json");
				}
				});
			});
			
			
		</script>		
		
		<link rel="stylesheet" href="<?php echo Config::getAsset('backend/assets/css/plugins/jquery.wysiwyg.css'); ?>" type="text/css"/>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/wysiwyg/jquery.wysiwyg.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/wysiwyg/wysiwyg.image.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/wysiwyg/wysiwyg.link.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo Config::getAsset('backend/assets/js/plugins/wysiwyg/wysiwyg.table.js'); ?>"></script>
		
		
	</head>
