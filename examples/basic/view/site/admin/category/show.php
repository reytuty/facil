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
<?php include(Config::getFolderView('/backend/category/nav-in.php')); ?>
<div class="maincontent">	

	<div class="left">
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable">
            <thead>
                <tr>
                    <th class="head0" width="30%">Segmento</th>
                    <th class="head1" width="30%">Categoria</th>
                    <th class="head0" width="30%">SubCategoria</th>
                    <th class="head1" width="10%">Ações</th>
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
            	function showCategory($categoryStd, $level){
            		$level0 = ($level == 0)?$categoryStd->name:"";
            		$level1 = ($level == 1)?$categoryStd->name:"";
            		$level2 = ($level == 2)?$categoryStd->name:"";
            		
            		$return = "<tr class=\"gradeX\">";
                    $return .= "<td class=\"con0\">".$level0."</td>";
                    $return .= "<td class=\"con1\">".$level1."</td>";
                    $return .= "<td class=\"con0\">".$level2."</td>";
                    $return .= "<td class=\"center con1\">
                    	<a href=\"".Config::getRootPath("backend/category/edit/level.$level/id.".$categoryStd->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/edit.png')."\" alt=\"\"></a>
                    	<a href=\"".Config::getRootPath("backend/category/delete/id.".$categoryStd->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/close.png')."\" alt=\"\"></a>                    	
                   	</td>
                   	";
                    return $return;
            	}
	            foreach($HttpContentResult->array_category as $categoryLevel0){
	            	echo showCategory($categoryLevel0, 0);
	            	foreach($categoryLevel0->__array_category as $categoryLevel1){
	            		echo showCategory($categoryLevel1, 1);
		            	foreach($categoryLevel1->__array_category as $categoryLevel2){
		            		echo showCategory($categoryLevel2, 2); 
		            	}
	            	}
	            }//end foreach 
	            ?>
                      
            </tbody>
            <tfoot>
                <tr>
                    <th class="head0">Segmento</th>
                    <th class="head1">Categoria</th>
                    <th class="head0">SubCategoria</th>
                    <th class="head1">Ações</th>
                </tr>
            </tfoot>
        </table>       
    </div><!--left-->
  </div>
  <br />
</body>
</html>