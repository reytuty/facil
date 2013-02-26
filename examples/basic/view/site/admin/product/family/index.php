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
</script>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/product/nav-in.php')); ?>
<div class="maincontent">	

	<div class="left">
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="example">
            <thead>
                <tr>
                    <th class="head1" width="90%">Familia</th>
                    <th class="head0" width="10%">Ações</th>
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
            	?>        
            	<tr class="gradeA">
                    <td><?php echo $row->title; ?></td>
                    <td>
                    	<?php
                    	echo"<a href=\"".Config::getRootPath("backend/product/family/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/edit.png')."\" alt=\"\"></a>
                    	<a href=\"".Config::getRootPath("backend/product/family/delete/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/close.png')."\" alt=\"\"></a>"
                    	?>
                    </td>
                </tr>                      
                <?php }} ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="head1">Familia</th>
                    <th class="head0">Ações</th>
                </tr>
            </tfoot>
        </table>       
    </div><!--left-->
  </div>
  <br />
</body>
</html>