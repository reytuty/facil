<?php 
    if(1==2){$HttpResult = new HttpResult();}
    $HttpContentResult = $HttpResult->getHttpContentResult();
    
//    Debug::print_r($HttpContentResult);
    
$head = new HtmlHeader($HttpContentResult->getHeader());
//adiciona css e js em array    
$head->addCSS(array('reset', 'home'));
$head->addJS(array('jquery', 'home'));
//adiciona chamada de arquivo separadamente
$head->addJS('reference_name', 'js/hoje.js');
$head->addCSS('other_reference_name', 'css/home');
//da echo
$head->show();
//assim retornaria
// echo $head->show(TRUE);
?>
<body>

<div id="main">

      <?php include "view/democrart/parts/header.php" ?>
      
	  <div id="main_content">
	  	
	  	
		<div id="box_left">
		
		<?php include "view/democrart/parts/menu.php" ?>
		
		 </div>
		  		   
		 <div id="box_right">
		 	<div style="padding:0 0 0 10px" class="<?php echo $HttpContentResult->content_module->tag ?>">		 
		 		<?php 
		 		if($HttpResult->getSuccess()){
		 			echo str_replace("&#034;", "'", $HttpContentResult->content_module->content);
		 		}  else {
		 			echo Translation::text("não encontrou o conteudo");
		 		}
		 		
		 		?>
			</div>
		 </div>
		
	</div>
	
	<!-- FOOTER -->
	
	<?php include "view/democrart/parts/footer.php" ?>
	
</div>


</body>
</html>
