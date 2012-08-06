<?php

	/**
	 * @author		: Alan Lucian M. Tormente
	 * @date		: 21/01/2011
	 * @version		: 1.0
	 * @description	: data to make a html form for file module
	 * 
	 * library/facil3/core/modules/file/FileFormView.class.php
	 */
include_once "library/facil3/core/modules/generic/FormView.class.php";
	 
class FileFormView extends FormView{
	
	
	protected $quantity = 0;
	protected $file;
	protected $id;
	protected $name;
	protected $description;
	protected $author;
	protected $order;
	protected $locale;
	protected $array_files;
	public function __construct(){
		parent::__construct();
		$this->ignoreMethod('setQuantity');
		$this->ignoreMethod('setArrayFiles');
	}
	
	public function getFormViewPath(){
		return "view/plugins/file.php";
		
	}
	
	public function setQuantity($quant = null){
		$this->quantity = $quant ;
	}
	public function getQuantity(){
		return $this->quantity ;
	}
	public function getArrayFiles(){
		return $this->array_files;
	}
	public function setArrayFiles($arrayFiles){
		$this->array_files = $arrayFiles;
	}
	
	public function setId($args = array()){
		$defs = array(
			'name'			=>	'file_info_id',
			'visible'		=>	false,
			'required'		=>	true,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_ID###',
		);
		$data = array_merge($defs,$args);
		$this->id = $data;
	}
	
	
	public function setFile($args = array()){
		$defs = array(
			'label'			=>	Translation::text('File'),
			'name'			=>	'Filedata',
			'visible'		=>	true,
			'required'		=>	true,
			'type'			=>	'file',
		);
		$data = array_merge($defs,$args);
		$this->file = $data;
	}
	
	public function setName($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Name'),
			'name'			=>	'file_info_name',
			'visible'		=>	false,
			'required'		=>	true,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_NAME###',
		);
		$data = array_merge($defs,$args);
		$this->name = $data;
	}
	
	public function setDescription($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Description'),
			'name'			=>	'file_info_description',
			'visible'		=>	false,
			'required'		=>	false,
			'type'			=>	'text',
			'value'			=>	'###VALUE_DESCRIPTION###',
		);
		$data = array_merge($defs,$args);
		$this->description = $data;
	}
	
	public function setAuthor($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Author'),
			'name'			=>	'file_info_author',
			'visible'		=>	false,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_AUTHOR###',
		);
		$data = array_merge($defs,$args);
		$this->author = $data;
	}


	public function setOrder($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Order'),
			'name'			=>	'file_info_order',
			'visible'		=>	false,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_ORDER###',
		);
		$data = array_merge($defs,$args);
		$this->order = $data;
	}
	
	public function setLocale($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Locale'),
			'name'			=>	'file_info_locale',
			'visible'		=>	false,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_LOCALE###',
		);
		$data = array_merge($defs,$args);
		$this->locale = $data;
	}
	
}
