<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	//Debug::print_r($HttpContentResult);
	
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
<?php include(Config::getFolderView('/backend/product/nav-detail.php')); ?>
<div class="maincontent">	

	<div class="left">
		<form method="post" name="linkCategory" action="<?php echo Config::getRootPath('backend/product/commit/id.'.$HttpContentResult->vo->id); ?>">
		<input type="hidden" name="to" value="backend/product/link/id.<?php echo $HttpContentResult->vo->id; ?>" />
		<div class="form_default">
		<span style="float:right;">
        <button>Salvar</button>
        </span>
        <br>
        <br>
		<table cellpadding="0" cellspacing="0" border="0" class="dyntable">
            <thead>
                <tr>
                    <th class="head0" width="33%">Segmento</th>
                    <th class="head1" width="33%">Categoria</th>
                    <th class="head0" width="34%">SubCategoria</th>
                </tr>
            </thead>
            <colgroup>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
            </colgroup>
            
            <tbody>
            	
            	<?php  
            	function showCategory($categoryStd, $level, $vo = NULL){
            		$level0 = ($level == 0)?$categoryStd->name:"";
            		$level1 = ($level == 1)?$categoryStd->name:"";
            		$level2 = ($level == 2)?$categoryStd->name:"";
					$level2id = ($level == 2)?$categoryStd->id:"";
            		
            		$return = "<tr class=\"gradeX\">";
                    $return .= "<td class=\"con0\">".$level0."</td>";
                    $return .= "<td class=\"con1\">".$level1."</td>";
					if($level==2){
						$checked = "";
						if($vo && in_array($level2id, $vo->categories_dad)){
							$checked = " checked = \"checked\"";
						} else {
							//$checked = " checked = \"__$level2id nao ".implode("|", $vo->categories_dad)."\"";
						}
                    	$return .= "<td class=\"con0\"><input type=\"checkbox\" $checked value=\"".$level2id."\" name=\"category[]\">&nbsp;&nbsp;&nbsp;".$level2."</td>";
					}else{
						$return .= "<td class=\"con0\">".$level2."</td>";	
					}                   
                    return $return;
            	}
	            foreach($HttpContentResult->array_category as $categoryLevel0){
	            	echo showCategory($categoryLevel0, 0);
	            	foreach($categoryLevel0->__array_category as $categoryLevel1){
	            		echo showCategory($categoryLevel1, 1);
		            	foreach($categoryLevel1->__array_category as $categoryLevel2){
		            		echo showCategory($categoryLevel2, 2, $HttpContentResult->vo); 
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
                </tr>
            </tfoot>        	
        </table>  
        <span style="float:right; margin-top:8px;">
        <button>Salvar</button>
        </span>
        </form>
        </div>     
    </div><!--left-->
  </div>
  <br />
</body>
</html>