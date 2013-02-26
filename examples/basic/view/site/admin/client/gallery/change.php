<?php
	$id = DataHandler::getValueByArrayIndex($_GET, "id");
	$ImageVO = new ImageVO();
	$ImageVO->setId($id, TRUE);
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

<div class="maincontent">
	<form action="<?php echo Config::getRootPath('/backend/image_backend/set_description/id.'.$id); ?>" method="post">
	
        	<div class="form_default">
    			<input type="hidden" value="backend/client/change/id.<?php echo $id; ?>" name="to" />
				 <p>
                	<label for="name">Descrição</label>
                	<div class="widgetbox inlineblock">
						<h3><span></span></h3>
                		<div class="content nopadding">
							<textarea id="wysiwyg" name="description" cols="130" rows="15"><?php echo $ImageVO->description;  ?></textarea>
						</div>
					 </div>							 
	            </p> 
               <p>
                	<button>Salvar</button>
               </p>
			</div>
    </div><!--left-->            
 </form>
</div>
</body>
</html>