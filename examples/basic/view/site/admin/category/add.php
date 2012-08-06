<?php
	$HttpContentResult = $HttpResult->getHttpContentResult();
	$id = (isset($HttpContentResult->vo->id))?$HttpContentResult->vo->id:"";
?>

<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/category/nav-in.php')); ?>
<div class="maincontent">	
	<form action="<?php echo Config::getRootPath('backend/category/commit/id'.$id); ?>" method="post">
        
        	<div class="form_default">        			
        		<?php
        		if(isset($HttpContentResult->array_category)){
        			if($HttpContentResult->array_category!=NULL){
        		?>       	 
                    <p>
                    	<label for="category_id">Superior</label>
                        <select name="category_id">
                          <?php
                          foreach ($HttpContentResult->array_category as $row) {                      
                          ?>
                          <option value="<?php echo $row->id; ?>" <?php if($row->id==$HttpContentResult->vo->category_id){echo('selected="selected"');} ?>><?php echo $row->name; ?></option>
                          <?php
						  }
                          ?>                        		
                        </select>
                    </p>
                 <?php
					}
				}
                 ?>
                    <p>
                    	<label for="name">Nome</label>
                        <input type="text" name="name" class="sf" />
                    </p>                    
                    <p>
                    	<button>Adicionar</button>
                    </p>
            </div><!--form-->
            
        
        </form>
</div>
</body>
</html>