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
		  url: ROOT_URL+'backend/info_segmento/update_active/id.'+id+"/active."+active+"/",
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
<?php include(Config::getFolderView('/backend/info_segmento/nav-in.php')); ?>
<div class="maincontent">	

	<div class="left">
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                	<th class="head0" width="5%">ID</th>
                    <th class="head1" width="35%">Página</th>
                    <th class="head0" width="35%">Slug</th>
                    <th class="head1" width="15%"></th>
                    <th class="head0" width="10%">Ações</th>
                </tr>
            </thead>
            <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
            </colgroup>            
            <tbody>    
            	<?php
            		if(isset($HttpContentResult->arrayContentsVO)){
            		foreach ($HttpContentResult->arrayContentsVO as $row) {
            			$checked = ($row->active)?" checked=checked ":"  ";						
            	?>        
            	<tr class="gradeA">
            		<td><?php echo $row->id; ?></td>
                    <td><?php echo $row->title; ?></td>
                    <td><?php echo $row->slug; ?></td>
                    <td></td>
                    <td>
                    	<?php
                    	echo"<a href=\"".Config::getRootPath("backend/info_segmento/change/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/edit.png')."\" alt=\"\"></a>
                    	<a href=\"".Config::getRootPath("backend/info_segmento/delete/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/close.png')."\" alt=\"\"></a>"
                    	?>
                    </td>
                </tr>                      
                <?php }} ?>
            </tbody>
            <tfoot>
                <tr>
                	<th class="head1">ID</th>
                    <th class="head1">Página</th>
                    <th class="head0">Slug</th>
                    <th class="head1"></th>
                    <th class="head0">Ações</th>
                </tr>
            </tfoot>
        </table>       
    </div><!--left-->
  </div>
  <br />
</body>
</html>