<?php include(Config::getFolderView('/backend/head.php')); ?>
<body class="bodygrey">
<?php include(Config::getFolderView('/backend/nav.php')); ?>
<?php include(Config::getFolderView('/backend/equipe/nav-in.php')); ?>
<div class="maincontent">	
	<form action="<?php echo Config::getRootPath('backend/equipe/commit/'); ?>" method="post">        
        	<div class="form_default">   
                      <p>
                    	<label for="name">Membro</label>
                        <input type="text" name="title" class="sf" value="" />
                    </p>                    
                    <p>
                    	<button>Adicionar</button>
                    </p>
            </div><!--form-->       
        </form>
</div>
</body>
</html>