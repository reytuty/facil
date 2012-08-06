<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#wysiwyg').wysiwyg({
		controls: {
			cut: { visible: true },
			copy: { visible: true },
			paste: { visible: true }
		}
	});
});
</script>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/info_segmento/nav-detail.php')); ?>
<div class="maincontent">	
	<form action="<?php echo Config::getRootPath('backend/info_segmento/commit/id.'.$id); ?>" method="post">
        
        	<div class="form_default">
        			<?php 
        			//Debug::print_r($HttpContentResult->vo->categories_dad);
        			foreach($HttpContentResult->vo->categories_dad as $cat){
        			?>
        			<input type="hidden" value="<?php echo $cat; ?>" name="category[]" />
        			<?php 
        			}
        			?>     
        			<input type="hidden" value="backend/info_segmento/change/id.<?php echo $id; ?>" name="to" />
                    <p>
                    	<label for="name">Titulo</label>
                        <input type="text" name="name" class="sf" value="<?php echo $HttpContentResult->vo->name;  ?>" />
                    </p>
                    <p>
                    	<label for="name">Subtitulo</label>
                        <input type="text" name="content" class="sf" value="<?php echo $HttpContentResult->vo->content;  ?>" />
                    </p>                            
                    <p>
	                 	<label for="hat">Cor</label>
	                    <input type="text" name="hat" class="sf" value="<?php echo $HttpContentResult->vo->hat;  ?>" />
               		</p>
                    <p>
                    	<label for="name">Texto</label>
                    	<div class="widgetbox inlineblock">
							<h3><span></span></h3>
                    		<div class="content nopadding">
								<textarea id="wysiwyg" name="description" cols="130" rows="15"><?php echo $HttpContentResult->vo->description;  ?></textarea>
							</div>
						 </div>							 
		            </p> 
		            <p>
                    	<button>Salvar</button>
                    </p>
            </div><!--form-->
            
        
        </form>
</div>
</body>
</html>