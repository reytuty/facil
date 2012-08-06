<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/client/nav-detail.php')); ?>
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
<script type="text/javascript">
jQuery(document).ready(function() {
	
	ROOT_PATH = '<?php echo CONFIG::getRootPath(); ?>';
	
	jQuery('#example').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
	$(':checkbox[name=segment]').click(function(){
			var id = parseInt($(this).attr("id").split("_")[1]);
			var segment_id = parseInt($(this).attr("id").split("_")[2]);
			var method = "add_segment/";
			if(!$(this).is(':checked')){
				method = "remove_segment/";
			}
			$.ajax({
				  url: ROOT_PATH+'backend/client/'+method,
				  dataType: 'json',
				  data: { id:id , segment_id:segment_id},
				  success: function(data){
						//alert("salvo");
					}
				});
		});
});
function toogleSelect(element){
	var id = parseInt($(element).attr("id").split("_")[1]);
	var product_id = parseInt($(element).attr("id").split("_")[2]);
	var method = "add_product/";
	if(!$(element).is(':checked')){
		method = "remove_product/";
	}
	$.ajax({
		  url: ROOT_PATH+'backend/client/'+method,
		  dataType: 'json',
		  data: { id:id , product_id:product_id},
		  success: function(data){
				//alert("salvo");
			}
		});
}

</script>

<div class="maincontent">
	<form action="<?php echo Config::getRootPath('backend/client/commit/id.'.$id); ?>" method="post">
	
        	<div class="form_default">        		
    			<?php 
    			//Debug::print_r($HttpContentResult);
    			foreach($HttpContentResult->vo->categories_dad as $cat){
    			?>
    			<input type="hidden" value="<?php echo $cat; ?>" name="category[]" />
    			<?php 
    			}
    			?>     
    			<input type="hidden" value="backend/client/change/id.<?php echo $id; ?>" name="to" />
                <p>
                	<label for="name">Cliente</label>
                    <input type="text" name="title" class="sf" value="<?php echo $HttpContentResult->vo->title;  ?>" />
                </p>
                <p>
                 	<label for="name">Ano</label>
                    <select name="name" class="sf" style="width:70px;">
                    <?php for ($i=2000; $i < 2031 ; $i++) { ?>
                    <option value="<?php echo $i; ?>" <?php if($HttpContentResult->vo->name==$i){echo 'selected';} ?>><?php echo $i; ?></option>	                      
                    <?php } ?>
					</select>
                </p>
                <p>
                 	<label for="author">URL 360</label>
                    <input type="text" name="author" class="sf" value="<?php echo $HttpContentResult->vo->author;  ?>" />
                </p>
                <p>
                 	<label for="template_url">Videos</label>
                    <input type="text" name="template_url" class="sf" value="<?php echo $HttpContentResult->vo->template_url;  ?>" />
                </p>
				<p>
					<label for="name">Segmento</label>
					<ul style="float:left; list-style: none;">
					<?php
						// /Debug::print_r($HttpContentResult->vo->array_segments);
						foreach($HttpContentResult->vo->array_segments as $segment){
							$segment->id;
							$segment->name;
							$checked = in_array($segment->id, $HttpContentResult->vo->array_selected_segment)?" checked=checked ":"  ";
							echo "<li>";
							?>
							<input <?php echo $checked; ?> id="segmento_<?php echo $HttpContentResult->vo->id; ?>_<?php echo $segment->id; ?>" type="checkbox" name="segment" value="<?php echo $segment->id; ?>"/>
							<?php 
							echo " {$segment->name}</li>";
						}
					?>
					</ul>
				</p> 
				<br>
				<br>
				<br>
				<br>
				<br>
				 <p>
                	<label for="name">Descrição</label>
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
			</div>
	<div class="left" style="margin-top:50px;">	        
		<h3>Produtos Vinculados</h3>
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                    <th class="head1" width="10%">Selecione</th>
                    <th class="head0" width="60%">Produtos</th>
                    <th class="head1" width="20%">Uso Interno</th>
                    <th class="head0" width="10%"></th>
                </tr>
            </thead>
            <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            </colgroup>            
            <tbody>    
            	<?php
            		if(isset($HttpContentResult->arrayContentsVO)){
            		foreach ($HttpContentResult->arrayContentsVO as $row) {
            			$img_array = $row->getImages(NULL, "tagged");
						$img_tag = "";
						if(count($img_array) > 0){
							$img = $img_array[0];
							//Debug::print_r($img);
							$url = Config::getRootPath("image/get_image/image_id.".$img->id."/max_width.30/max_height.30/crop.1/");
							$img_tag = "<img src=\"$url\" />";
						}
						//verifica se está checado
						$checked = in_array($row->id, $HttpContentResult->vo->products_links)?" checked=checked ":"  ";
						
            	?>        
            	<tr class="gradeA">
                    <td><input <?php echo $checked; ?> onclick="toogleSelect(this)"  id="check_<?php echo $HttpContentResult->vo->id; ?>_<?php echo $row->id; ?>" type="checkbox" name="produtos" value="<?php echo $row->id; ?>"/><span style="color:#F7F7F7;"><?php echo $checked; ?></span></td>
                    <td><?php echo $row->title; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php //echo $img_tag; ?></td>
                </tr>                      
                <?php }} ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="head1">Selecione</th>
                    <th class="head0">Produto</th>
                    <th class="head1">Uso Interno</th>
                    <th class="head0"></th>
                </tr>
            </tfoot>
        </table>       
    </div><!--left-->            
 </form>
</div>
</body>
</html>