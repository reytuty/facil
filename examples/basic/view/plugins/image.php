<div class='images'>
	<dl>
		<dt><?php echo $formView->getFormLabel();?></dt>
		 
			<?php
			
			$module = $formView->getFieldData();
			
			$quantity = $formView->getQuantity();
			
			$multi_fields = $quantity == "*" ? true : false;
			$quantity = $multi_fields ? 1 : $quantity; 
			
			$model = "<div class='box rad' id='image_###VALUE_ID###'>###IMAGE_TAG### ###DELETE_TAG### ###IMAGE_URL###";
				$i = 0;
				foreach ( $module as $field){
      				
					if(isset($field->visible) && $field->visible === FALSE){
						$model.= "<input type='hidden' name='{$field->name}[]' value='{$field->value}' />";
						continue;
					}
					$model.= "<label for='content_{$field->name}_{$i}'>{$field->label}</label>"; 
					
					switch($field->type){
						case "simpleText":
							$model.= "<input type='text' size='40' name='{$field->name}[]' id='content_{$field->name}_{$i}' value='{$field->value}' /><br/>";
							break;
						case "file":
							$model.= "<input type='file' size='40' name='{$field->name}[]' id='content_{$field->name}_{$i}' /><br/>";
							break;	
						case "checkbox":
							$checked = $field->value == 1 ? " checked " : "" ;
							$model.= "<input type='checkbox' $checked name='{$field->name}[]' id='content_{$field->name}_{$i}' value='1' /><br/>";
							break;	
						
						case "text":
							$model.= "<textarea  name='{$field->name}[]' id='content_{$field->name}_{$i}' cols='60' rows='10'>{$field->value}</textarea><br/>";
							break; 
						
						$i++;	
					}
				}
				$model.= "</div>";
				
				
			echo "<div id='images_model' class='multi_input_model' style='display:none'>".str_replace("###IMAGE_URL###", "", $model)."</div>";
			
			echo "<div id='images_container'>";
			
			$printed_fields = 0;
			foreach ($HttpContentResult->arrayVariable->array_image as $image){
//							print_r($image);
				$imagePath =  Config::getRootPath("image/get_image/image_id.{$image->id}/max_width.150/max_heigth.150");		
				
				// $delete_URL = Config::getRootPath("image/get_image/id.{$image->id}/max_width.150/max_heigth.150");
					
				$data = $model;
				preg_match_all("/###VALUE_([a-zA-Z_]+)###/", $model, $out);
				
				for($i = 0; $i<sizeof($out[0]); $i++ ){
					$attr = strtolower($out[1][$i]);
					$data = str_replace($out[0][$i], $image->$attr, $data );		
							
				}
				$data = str_replace("###IMAGE_TAG###", "<img src='{$imagePath}' />", $data );
				$data = str_replace("###DELETE_TAG###", "<a class='delete btn' href='image_{$image->id}'>". Translation::text('delete') ."</a>", $data );		
				
//				print_r($formView->getShowImageUrl());
				
				if($formView->getShowImageUrl()){
					$data = str_replace("###IMAGE_URL###", "<spam class='url'><b>Url da imagem:</b>".Config::getRootPath("image/get_image/natural_size.true/direct_show.1/image_id.".$image->id), $data )."</spam>";
				}else{
					$data = str_replace("###IMAGE_URL###", "", $data);
				}
				echo $data;
				$printed_fields ++;
				/// die;
				
			}
			
			$model = preg_replace( '/###([a-zA-Z_]+)###/','' ,$model );
			
			for($i = $printed_fields ; $i < $quantity ; $i++){
				
				echo $model;
				
			}
			echo "</div>";
				
			if($multi_fields)
				echo "<input class='add_more_input btn' id='add_more_images' type='button' name='"  . Translation::text('Add more') ."'  value='". Translation::text("add more") ." '/>";
			
			
			?>
		</dd>
	</dl>
</div>