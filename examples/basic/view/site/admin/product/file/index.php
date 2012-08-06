<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
	//print_r($HttpContentResult->array_files);
	//print_r($HttpContentResult->vo);
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/product/nav-detail.php')); ?>
<div class="maincontent">
<a href="<?php echo Config::getRootPath('backend/product/file/upload/id.'.$HttpContentResult->vo->id); ?>" class="iconlink" id="upload" style="margin-top:15px; margin-left: 15px;"><img src="<?php echo Config::getAsset('backend/assets/images/icons/small/black/video.png'); ?>" class="mgright5" alt=""> <span>Upload</span></a>
    <div class="left">
        <div id="gallery" class="gallery">        
            <ul class="submenu">
                
                <li class="current"><a href="#listview">List View</a></li>
            </ul>
            <br />               
            <div id="listview" class="listview" style="display:block;">
                    
                <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
                    <colgroup>
                    	<col class="head0" width="5%" />
                        <col class="head1" width="40%" />
                        <col class="head1" width="10%" />
                        <col class="head0" width="30%" />
                        <col class="head1" width="15%" />
                    </colgroup>
                    <tr>
                        <td align="center">ID</td>
                        <td align="center">Arquivo</td>
                        <td align="center">Extensão</td>
                        <td></td>
                        <td align="center">Ações</td>
                    </tr>
                </table>
                <div class="sTableWrapper">
                	<input type="hidden" name="category_id" id="category_id" value="<?php echo $HttpContentResult->vo->id; ?>">
                    <table cellpadding="0" cellspacing="0" class="sTable" width="100%">
                        <colgroup>
                            <col class="con0" width="5%" />
                            <col class="con1" width="40%" />
                            <col class="con1" width="10%" />
                            <col class="con0" width="30%" />
                            <col class="con1" width="15%" />
                        </colgroup>
                        <tbody class="ui-sortable">
                        <?php
		            		if(isset($HttpContentResult->array_files)){
		            		foreach ($HttpContentResult->array_files as $row) {						
		            	?> 
                        <tr>
                            <td align="center" id="item<?php echo $row->id; ?>"><?php echo $row->id; ?></td>
                            <td>
                            <?php
                            $nome = explode("/", DataHandler::returnFilenameWithoutExtension($row->url));
							$nome = $nome[count($nome)-1];
                            echo($nome);
                            ?>
                            </td>
                            <td align="center"><a href="<?php echo $row->url;?>"><?php echo strtoupper(DataHandler::returnExtensionOfFile($row->url)); ?></a></td>
                            <td><?php echo DataHandler::bytesInString(filesize($row->url));?></td>
                            <td align="center"><?php
                    	echo"<a href=\"".Config::getRootPath("backend/product/delete_file/product_id.".$HttpContentResult->vo->id."/id.".$row->id)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/close.png')."\" alt=\"\"></a>"
                    	?></td>
                        </tr>
                         <?php 
		                }}
		                ?>  
		                </tbody>
                    </table>
                 </div><!--sTableWrapper-->
                    
            </div><!-- listview -->

		</div><!--gallery-->
    </div><!--left-->
    
    <br clear="all" />
</div>	
</body>
</html>