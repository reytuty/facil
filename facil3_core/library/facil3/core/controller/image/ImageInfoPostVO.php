<?php

/**
 * VO de apoio da controller FacilImage
 * @author Renato Miawaki
 *
 */
class ImageInfoPostVO{
	//info sobre dados gravados banco junto com a url da imagem - ou seja, na ImageVO
		/**
		 * @var int se enviar o id ele atualiza, em vez de criar
		 */
		public $image_info_id;
		public $image_info_type;
		public $image_info_active;
		public $image_info_url;
		public $image_info_name;
		public $image_info_description;
		public $image_info_order;
		public $image_info_locale;
		public $image_info_author;
	//info sobre o arquivo
	public $file_data;
	//caminho do arquivo
	public $file_url;
	
	//info de requisição de thumb
	public $request_image_id;
	public $request_image_url;
	public $request_max_width;
	public $request_max_height;
	public $request_crop;
	public $request_direct_show;
	public $request_natural_size;
	
	//info de vinculo
	public $table;
	public $table_id;
	
	public function __construct($array_fetch = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
	}
	public function setFetchArray($array){
		$this->image_info_id				= DataHandler::getValueByArrayIndex($array, "image_info_id");
		$this->image_info_type				= DataHandler::getValueByArrayIndex($array, "image_info_type");
		$this->image_info_active			= DataHandler::getValueByArrayIndex($array, "image_info_active");
		$this->image_info_url				= DataHandler::getValueByArrayIndex($array, "image_info_url");
		$this->image_info_name				= DataHandler::getValueByArrayIndex($array, "image_info_name");
		$this->image_info_description		= DataHandler::getValueByArrayIndex($array, "image_info_description");
		$this->image_info_order				= DataHandler::getValueByArrayIndex($array, "image_info_order");
		$this->image_info_locale			= DataHandler::getValueByArrayIndex($array, "image_info_locale");
		$this->image_info_author			= DataHandler::getValueByArrayIndex($array, "image_info_author");
		
		$this->request_image_id				= DataHandler::getValueByArrayIndex($array, "image_id");
		$this->request_image_url			= DataHandler::getValueByArrayIndex($array, "image_url");
		
		//abaixo, ATENÇÃO: Filedata
		$this->file_data 					= DataHandler::getValueByArrayIndex($array, "Filedata");
		$this->file_url 					= DataHandler::getValueByArrayIndex($array, "file_url");
		
		//info de requisição de thumb
		$this->request_max_width 			= DataHandler::getValueByArrayIndex($array, "max_width");
		$this->request_max_height 			= DataHandler::getValueByArrayIndex($array, "max_height");
		$this->request_crop 				= DataHandler::getValueByArrayIndex($array, "crop");
		$this->request_direct_show 			= DataHandler::getValueByArrayIndex($array, "direct_show");
		$this->request_natural_size 		= DataHandler::getValueByArrayIndex($array, "natural_size");
		
		//info de vinculo
		$this->table 						= DataHandler::getValueByArrayIndex($array, "table");
		$this->table_id 					= DataHandler::getValueByArrayIndex($array, "table_id");
		
	}
}