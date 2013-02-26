<?php

	/**
	 * @author		: Alan Lucian M. Tormente
	 * @date		: 21/01/2011
	 * @version		: 1.0
	 * @description	: data to make a html form for image module
	 */
include_once "library/facil3/core/modules/generic/FormView.class.php";
	 
class ImageFormView extends FormView{
	
	
	protected $quantity = 0;
	protected $image;
	protected $id;
	protected $name;
	protected $description;
	protected $author;
	protected $order;
	protected $locale;
	
	public function __construct(){
		parent::__construct();
		$this->ignoreMethod('setQuantity');
		
	}
	
	public function getFormViewPath(){
		return "view/plugins/image.php";
		
	}
	
	public function setQuantity($quant = null){
			$this->quantity = $quant ;
	}
	public function getQuantity(){
		return $this->quantity ;
	}
	
	public function setId($args = array()){
		$defs = array(
			'name'			=>	'image_info_id',
			'visible'		=>	false,
			'required'		=>	true,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_ID###',
		);
		$data = array_merge($defs,$args);
		$this->id = $data;
	}
	
	
	public function setImage($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Image'),
			'name'			=>	'image',
			'visible'		=>	true,
			'required'		=>	true,
			'type'			=>	'file',
		);
		$data = array_merge($defs,$args);
		$this->image = $data;
	}
	
	public function setName($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Name'),
			'name'			=>	'image_info_name',
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
			'name'			=>	'image_info_description',
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
			'name'			=>	'image_info_author',
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
			'name'			=>	'image_info_order',
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
			'name'			=>	'image_info_locale',
			'visible'		=>	false,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	'###VALUE_LOCALE###',
		);
		$data = array_merge($defs,$args);
		$this->locale = $data;
	}
	
}
