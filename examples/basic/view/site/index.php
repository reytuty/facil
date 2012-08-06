<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();

	//Debug::print_r($HttpContentResult);
	
	//abaixo, um html escrito sem uso de classes de view
	//Config::getAsset($caminho_a_partir_da_view);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>Teto</title>
		<meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    	<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" media="screen" href="<?php echo Config::getAsset('/assets/css/reset.css'); ?>" />
		<link rel="stylesheet" media="screen" href="<?php echo Config::getAsset('/assets/css/home.css'); ?>" />
		<script type="text/javascript"><?php include(Config::getFolderView('/assets/js/home.php')); ?></script>
	</head>
	<body>
		<div id="content">
			<div id="header">
				<ul>
					<li style="margin-left:60px;"><a href="<?php echo Config::getRootPath('produtos'); ?>">Produtos</a></li>
					<li><a href="<?php echo Config::getRootPath('clientes'); ?>">clientes</a></li>
				</ul>
				<div id="line"></div>
			</div>
			<div id="products">
				<?php foreach ($HttpContentResult->produtos as $produto) { ?>
				<div class="products" id="products-<?php echo $i; ?>">
					<?php 
					Debug::print_r($produto);
					?>
				</div>
				<?php } ?>	
			</div>
	<div>
</body>
</html>