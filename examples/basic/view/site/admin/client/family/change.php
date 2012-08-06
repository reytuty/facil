<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/client/nav-in.php')); ?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#example').dataTable( {
		"sPaginationType": "full_numbers"
	});
});
function toogleSelect(element){
	var id = parseInt($(element).attr("id").split("_")[1]);
	var client_id = parseInt($(element).attr("id").split("_")[2]);
	var method = "add_client/";
	if(!$(element).is(':checked')){
		method = "remove_client/";
	}
	$.ajax({
		  url: ROOT_PATH+'backend/client/family/'+method,
		  dataType: 'json',
		  data: { id:id , client_id:client_id},
		  success: function(data){
				//alert("salvo");
			}
		});
}
</script>

	

<div class="maincontent">	
	<form action="<?php echo Config::getRootPath('backend/client/family/commit/id.'.$id); ?>" method="post">        
        	
	<div class="left">
		<div class="form_default">
			<?php 
			//Debug::print_r($HttpContentResult);
			foreach($HttpContentResult->vo->categories_dad as $cat){
			?>
			<input type="hidden" value="<?php echo $cat; ?>" name="category[]" />
			<?php 
			}
			?> 
			<input type="hidden" value="backend/client/family/show" name="to" />
	        <p>
	        	<input type="hidden" name="title" class="sf" value="<?php echo $HttpContentResult->vo->title;  ?>" />
	        </p>
	        <p>
	        	<b>Fam&iacute;lia <?php echo $HttpContentResult->vo->title;  ?></b>
	        </p>
      	</div>
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                    <th class="head1" width="10%">Selecione</th>
                    <th class="head0" width="30%">Produtos</th>
                    <th class="head1" width="20%">Uso Interno</th>
                    <th class="head0" width="40%"></th>
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
                    <td><input <?php echo $checked; ?> onclick="toogleSelect(this)" id="check_<?php echo $HttpContentResult->vo->id; ?>_<?php echo $row->id; ?>" type="checkbox" name="produtos" value="<?php echo $row->id; ?>"/><span style="color:#F7F7F7;"><?php echo $checked; ?></span></td>
                    <td><?php echo $row->title; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php //echo $img_tag; ?></td>
                </tr>                      
                <?php }} ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="head1">Selecione</th>
                    <th class="head0">Produtos</th>
                    <th class="head1">Uso Interno</th>
                    <th class="head0"></th>
                </tr>
            </tfoot>
        </table>       
    </div><!--left-->
    </div><!--form-->       
</form>
</div>
</body>
</html>