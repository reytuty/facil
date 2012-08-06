<?php

/**
 * @desc VO de apoio da controller FacilImage
 * @author Mauricio Amorim
 *
 */
class FileInfoPostVO{
	//info sobre dados gravados banco junto com a url da filem - ou seja, na ImageVO
	public $file_info_id;
	public $file_info_type;
	public $file_info_active;
	public $file_info_url;
	public $file_info_name;
	public $file_info_description;
	public $file_info_order;
	public $file_info_locale;
	public $file_info_author;		
		
	//info sobre o arquivo
	public $file_data;
	//caminho do arquivo
	public $file_url;
	
	//info de requisição de thumb
	public $request_file_id;
	public $request_file_url;
	
	//info de vinculo
	public $table;
	public $table_id;
	
	public function __construct($array_fetch = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
	}
	public function setFetchArray($array){
		//print_r($array);
		$this->file_info_id				= DataHandler::getValueByArrayIndex($array, "file_info_id");
		$this->file_info_type			= DataHandler::getValueByArrayIndex($array, "file_info_type");
		$this->file_info_active			= DataHandler::getValueByArrayIndex($array, "file_info_active");
		$this->file_info_url			= DataHandler::getValueByArrayIndex($array, "file_info_url");
		$this->file_info_name			= DataHandler::getValueByArrayIndex($array, "file_info_name");
		$this->file_info_description	= DataHandler::getValueByArrayIndex($array, "file_info_description");
		$this->file_info_order			= DataHandler::getValueByArrayIndex($array, "file_info_order");
		$this->file_info_locale			= DataHandler::getValueByArrayIndex($array, "file_info_locale");
		$this->file_info_author			= DataHandler::getValueByArrayIndex($array, "file_info_author");
		
		$this->request_file_id			= DataHandler::getValueByArrayIndex($array, "file_id");;
		$this->request_file_url			= DataHandler::getValueByArrayIndex($array, "file_url");;
		
		//abaixo, ATENÇÃO: Filedata recebe o arquivo a ser uplodado
		$this->file_data 				= DataHandler::getValueByArrayIndex($array, "Filedata");
		$this->file_url 				= DataHandler::getValueByArrayIndex($array, "file_url");
		
		//info de vinculo
		$this->table 					= DataHandler::getValueByArrayIndex($array, "table");
		$this->table_id 				= DataHandler::getValueByArrayIndex($array, "table_id");
		
	}
}