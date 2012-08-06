<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
	//print_r($HttpContentResult->vo);
?>
<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/info/nav-detail.php')); ?>
<div class="maincontent">
<a href="<?php echo Config::getRootPath('backend/info/gallery/upload/type.'.$HttpContentResult->gallery_type.'/id.'.$HttpContentResult->vo->id); ?>" class="iconlink" id="upload" style="margin-top:15px; margin-left: 15px;"><img src="<?php echo Config::getAsset('backend/assets/images/icons/small/black/video.png'); ?>" class="mgright5" alt=""> <span>Upload</span></a>
    <div class="left">
        <div id="gallery" class="gallery">        
            <ul class="submenu">
                <li class="current"><a href="#gridview">Grid View</a></li>
                <li><a href="#listview">List View</a></li>
            </ul>
            
            <br />
            
            <div id="gridview" class="thumbview">
            	<?php
            		if(isset($HttpContentResult->array_images)){
            		foreach ($HttpContentResult->array_images as $row) {						
            	?> 
                <ul>
                    <li>
                        <div class="thumb">
                            <img src="<?php echo Config::getRootPath("image/get_image/image_id.".$row->id."/max_width.255/max_height.255") ?>" alt="" />
                            <div class="info">                             
                                <p class="menu">
                                   
                                </p>
                            </div><!--info-->
                        </div><!--thumb-->
                    </li>                       
                </ul>  
                <?php 
                }}
                ?>  	
            </div><!--gridview-->
                
            <div id="listview" class="listview">
                    
                <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
                    <colgroup>
                    	<col class="head0" width="5%" />
                        <col class="head1" width="10%" />
                        <col class="head0" width="70%" />
                        <col class="head1" width="15%" />
                    </colgroup>
                    <tr>
                        <td align="center">ID</td>
                        <td align="center">Thumb</td>
                        <td align="center">Name</td>
                        <td align="center">Ações</td>
                    </tr>
                </table>
                <div class="sTableWrapper">
                	<input type="hidden" name="category_id" id="category_id" value="<?php echo $HttpContentResult->vo->id; ?>">
                	<input type="hidden" name="linked_table" id="linked_table" value="<?php echo $HttpContentResult->gallery_type; ?>">
                    <table cellpadding="0" cellspacing="0" class="sTable" width="100%">
                        <colgroup>
                            <col class="con0" width="5%" />
                            <col class="con1" width="10%" />
                            <col class="con0" width="70%" />
                            <col class="con0" width="15%" />
                        </colgroup>
                        <tbody class="ui-sortable">
                        <?php
		            		if(isset($HttpContentResult->array_images)){
		            		foreach ($HttpContentResult->array_images as $row) {						
		            	?> 
                        <tr>
                            <td align="center" id="item<?php echo $row->id; ?>"><?php echo $row->id; ?></td>
                            <td align="center"><img src="<?php echo Config::getRootPath("image/get_image/image_id.".$row->id."/max_width.80/max_height.255") ?>" alt="" /></td>
                            <td><?php echo $row->url; ?></td>
                            <td align="center"><?php
                    	echo"<a href=\"".Config::getRootPath("backend/info/delete_image/product_id.".$HttpContentResult->vo->id."/id.".$row->id."/type.".$HttpContentResult->gallery_type)."\" class=\"iconlink2\"><img src=\"".Config::getAsset('backend/assets/images/icons/small/black/close.png')."\" alt=\"\"></a>"
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