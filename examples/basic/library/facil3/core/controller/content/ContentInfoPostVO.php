<?php

/**
 * VO de apoio da controller FacilContent
 * @author Mauricio Amorim
 *
 */
class ContentInfoPostVO{
	
	CONST UPLOAD_FILE_TYPE = array('image', 'file');
	
	//compos da tabela parametro
 	 public $name;
	 public $title;
	 public $hat;
	 public $description;
	 public $content;
	 public $author;
	 public $template_url;
	 public $slug;
	 public $key_words;
	 public $date;
	 public $date_in;
	 public $date_out;
	 public $order;
  	
	
	// array de arquivos gerais
		public $arr_uploaded_files = array();
	
	//info de requisição de content
	public $request_table_id;
	public $request_table;
	public $request_content_id;
	public $request_slug;
	public $request_locale;
	public $request_quant_start = NULL;
	public $request_quant_limit = NULL;
	public $request_type_of_return;
	
	public function __construct($array_fetch = NULL, $arr_files = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
		
		if(is_array($array_fetch)){
			foreach (ContentInfoPostVO::UPLOAD_FILE_TYPE as $type){
				if(isset($arr_files[$type])){
					for($i = 0 ; $i< sizeof($arr_files[ $type ]); $i++){
						$data = array(
							$type . "_info_active" 			=> $array_fetch[ $type . '_active'][$i],
							$type . "_info_type" 			=> $array_fetch[ $type . '_type'][$i],
							$type . "_info_author" 			=> $array_fetch[ $type . '_author'][$i],
							$type . "_info_name" 			=> $array_fetch[ $type . '_name'][$i],
							$type . "_info_description" 	=> $array_fetch[ $type . '_description'][$i],
							$type . "_info_order"		 	=> $array_fetch[ $type . '_order'][$i],
							$type . "_info_locale" 			=> $array_fetch[ $type . '_locale'][$i],
							"file_data" 					=> $arr_files[ $type ][$i]
						);
						$arr_uploaded_files[$type][] = $data;
					}
				}
				
			}
			
			
		}
	}
	public function setFetchArray($array){
		//print_r($array);
		$this->id 						= DataHandler::getValueByArrayIndex($array, "id");
		$this->active 					= DataHandler::getValueByArrayIndex($array, "active");
		$this->name 					= DataHandler::getValueByArrayIndex($array, "name");
		$this->title 					= DataHandler::getValueByArrayIndex($array, "title");
		$this->hat						= DataHandler::getValueByArrayIndex($array, "hat");
		$this->description 				= DataHandler::getValueByArrayIndex($array, "description");
		$this->content 					= DataHandler::getValueByArrayIndex($array, "content");
		$this->author 					= DataHandler::getValueByArrayIndex($array, "author");
		$this->template_url 			= DataHandler::getValueByArrayIndex($array, "template_url");
		$this->slug 					= DataHandler::getValueByArrayIndex($array, "slug");
		$this->key_words 				= DataHandler::getValueByArrayIndex($array, "key_words");
		$this->date 					= DataHandler::getValueByArrayIndex($array, "date"); 
		$this->date_in 					= DataHandler::getValueByArrayIndex($array, "date_in"); 
		$this->date_out	 				= DataHandler::getValueByArrayIndex($array, "date_out"); 
		$this->order 					= DataHandler::getValueByArrayIndex($array, "order"); 
		
		$this->request_table_id			= DataHandler::getValueByArrayIndex($array, "table_id");
		$this->request_table			= DataHandler::getValueByArrayIndex($array, "table");
		$this->request_content_id		= DataHandler::getValueByArrayIndex($array, "content_id");
		$this->request_slug				= DataHandler::getValueByArrayIndex($array, "slug");
		$this->request_locale			= DataHandler::getValueByArrayIndex($array, "locale");
		$this->request_quant_start		= DataHandler::getValueByArrayIndex($array, "quant_start");
		$this->request_quant_limit		= DataHandler::getValueByArrayIndex($array, "quant_limit");
		$this->request_type_of_return	= DataHandler::getValueByArrayIndex($array, "type_of_return");
	}
}