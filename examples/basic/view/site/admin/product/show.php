<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#example').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
});
function tooglePublished(element){
	var id = parseInt($(element).attr("id").split("_")[1]);
	var active = 1;
	if(!$(element).is(':checked')){
		active = 0;
	}
	$.ajax({
		  url: ROOT_PATH+'backend/product/update_active/id.'+id+"/active."+active+"/",
		  dataType: 'json',
		  data: { },
		  success: function(data){
				//alert("salvo");
			}
		});
}
</script>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/product/nav-in.php')); ?>
<div class="maincontent">	

	<div class="left">
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                	<th class="head1" width="5%">ID</th>
                	<th class="head0" width="10%">Publicado</th>
                    <th class="head1" width="35%">Produto</th>
                    <th class="head0" width="25%">Slug</th>
                    <th class="head1" width="15%">Uso Interno</th>
                    <th class="head0" width="10%">Ações</th>
                </tr>
            </thead>
            <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            </colgroup>            
            <tbody>    
            	<?php
            		if(isset($HttpContentResult->arrayContentsVO)){
            		foreach ($HttpContentResult->arrayContentsVO as $row) {
            			$checked = ($row->active)?" checked=checked ":"  ";						
            	?>        
            	<tr class="gradeA">
            		<td><?php echo $row->id; ?></td>
            		<td><input <?php echo $checked; ?> onclick="tooglePublished(this)" id="check_<?php echo $row->id; ?>" type="checkbox" name="content_" value="<?php echo $row->id; ?>"/><span style="color:#EEEEEE;"><?php echo $checked; ?></span></td>
                    <td><a href="<?php echo Config::getRootPath("produto/id.".$row->id); ?>"/><?php echo $row->title; ?></a></td>
                    <td><?php echo $row->slug; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td>
                    	<?php
                    	echo"<a href=\"".Config::getRootPath("backend/product/change/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/edit.png')."\" alt=\"\"></a>
                    	<a href=\"".Config::getRootPath("backend/product/delete/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/close.png')."\" alt=\"\"></a>"
                    	?>
                    </td>
                </tr>                      
                <?php }} ?>
            </tbody>
            <tfoot>
                <tr>
                	<th class="head1">ID</th>
                	<th class="head0">Publicado</th>
                    <th class="head1">Produto</th>
                    <th class="head0">Slug</th>
                    <th class="head1">Uso Interno</th>
                    <th class="head0">Ações</th>
                </tr>
            </tfoot>
        </table>       
    </div><!--left-->
  </div>
  <br />
</body>
</html>