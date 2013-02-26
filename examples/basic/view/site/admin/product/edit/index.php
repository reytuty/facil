<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
	print_r($HttpContentResult->vo);
?>

<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/product/nav-in.php')); ?>
<div class="maincontent">	
	<form action="<?php echo Config::getRootPath('backend/product/commit/id.'.$id); ?>" method="post">
        
        	<div class="form_default">     
                    <p>
                    	<label for="name">Produto</label>
                        <input type="text" name="title" class="sf" value="<?php echo $HttpContentResult->vo->title;  ?>" />
                    </p>                    
                    <p>
                    	<button>Editar</button>
                    </p>
            </div><!--form-->
            
        
        </form>
</div>
</body>
</html>