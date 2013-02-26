<div class='files'>
	<dl>
		<dt><?php echo $formView->getFormLabel();?></dt>
		 
			<?php
			
			 $module = $formView->getFieldData();
			
			
			$quantity = $formView->getQuantity();
			
			$multi_fields = $quantity == "*" ? true : false;
			$quantity = $multi_fields ? 1 : $quantity; 
			
			$model = "<div class='box rad' id='file_###VALUE_ID###'>###FILE_TAG### ###DELETE_TAG###";
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
				
				
			//echo "<div id='files_model' class='multi_input_model' style='display:none'>{$model}</div>";
			
			echo "<div id='files_container'>";
			
			$printed_fields = 0;
			//Debug::print_r($HttpContentResult->arrayVariable->array_file);
			
			$array_file = $formView->getArrayFiles();
			if($array_file)
			foreach ($array_file as $file){
				$extensao =  DataHandler::returnExtensionOfFile($file->url);
				//procurando o arquivo que simbolize esse icone
				$filePath =  Config::getRootPath("/image/get_image/url.view[,]forum[,]assets[,]img[,]file[,]file_icon[:]jpg/max_width.30/max_heigth.30");
				//ve se o arquivo do icone existe
				$url_ico = Config::getFolderView("assets/img/file/{$extensao}_icon.gif");
				if(file_exists($url_ico)){
					$url_ico 	= str_replace("/", "[,]", $url_ico);
					$url_ico 	= str_replace(".gif", "[:]gif", $url_ico);
					$filePath 	=  Config::getRootPath("/image/get_image/url.{$url_ico}/max_width.30/max_heigth.30");
				}
						
				// 		view/forum/assets/img/file/file_icon.jpg
				// $delete_URL = Config::getRootPath("file/get_file/id.{$file->id}/max_width.150/max_heigth.150");
					
				$data = $model;
				preg_match_all("/###VALUE_([a-zA-Z_]+)###/", $model, $out);
				
				for($i = 0; $i<sizeof($out[0]); $i++ ){
					$attr = strtolower($out[1][$i]);
					$data = str_replace($out[0][$i], $file->$attr, $data );
				}
				$data = str_replace("###FILE_TAG###", "<img src='{$filePath}' alt='' title='' style='float:left;margin: 0 0 5px 0;display:block' />", $data );
				//file_{$file->id}
				$data = str_replace("###DELETE_TAG###", "<a class='delete btn' href='#' onclick='$.post(\"".Config::getRootPath("file/delete/id.{$file->id}")."\", function(a){window.location.reload();});'>". Translation::text('delete') ."</a>", $data );		
				
				echo $data;
				$printed_fields ++;
				//die;
			}
			
			$model = preg_replace( '/###([a-zA-Z_]+)###/','' ,$model );
			
			for($i = $printed_fields ; $i < $quantity ; $i++){
				
				echo $model;
				
			}
			echo "</div>";
				
			if($multi_fields) {
				echo "<input class='add_more_input btn' id='add_more_files' type='button' name='"  . Translation::text('Add more') ."'  value='". Translation::text("add more") ." '/>";
            }
			
			?>
		</dd>
	</dl>
</div>