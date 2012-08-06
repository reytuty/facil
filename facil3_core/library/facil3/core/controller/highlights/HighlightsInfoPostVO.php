<?php

/**
 * VO de apoio da controller FacilHighlights
 * @author Mauricio Amorim
 *
 */
class HighlightsInfoPostVO{
	//compos da tabela parametro
	 public $id;
	 public $active;
 	 public $name;
	 public $link;
	 public $content;
	 public $image_url;
	 public $date;
	 public $date_in;
	 public $date_out;
	 public $order;
  	
	//info de requisição de content
	public $request_table_id;
	public $request_table;
	public $request_highlight_id;
	public $request_locale;
	public $request_quant_start = NULL;
	public $request_quant_limit = NULL;
	public $request_type_of_return;
	
	public function __construct($array_fetch = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
	}
	public function setFetchArray($array){
		//print_r($array);
		$this->id 						= DataHandler::getValueByArrayIndex($array, "id");
		$this->active 					= DataHandler::getValueByArrayIndex($array, "active");
		$this->name 					= DataHandler::getValueByArrayIndex($array, "name");
		$this->link 					= DataHandler::getValueByArrayIndex($array, "link");
		$this->content 					= DataHandler::getValueByArrayIndex($array, "content");
		$this->image_url 				= DataHandler::getValueByArrayIndex($array, "image_url");
		$this->date 					= DataHandler::getValueByArrayIndex($array, "date"); 
		$this->date_in 					= DataHandler::getValueByArrayIndex($array, "date_in"); 
		$this->date_out	 				= DataHandler::getValueByArrayIndex($array, "date_out"); 
		$this->order 					= DataHandler::getValueByArrayIndex($array, "order"); 
		
		$this->request_table_id			= DataHandler::getValueByArrayIndex($array, "table_id");
		$this->request_table			= DataHandler::getValueByArrayIndex($array, "table");
		$this->request_highlights_id	= DataHandler::getValueByArrayIndex($array, "highlights_id");
		$this->request_locale			= DataHandler::getValueByArrayIndex($array, "locale");
		$this->request_quant_start		= DataHandler::getValueByArrayIndex($array, "quant_start");
		$this->request_quant_limit		= DataHandler::getValueByArrayIndex($array, "quant_limit");
		$this->request_type_of_return	= DataHandler::getValueByArrayIndex($array, "type_of_return");
	}
}