<?php

/**
	 * @author		: Alan Lucian M. Tormente
	 * @date		: 17/01/2011
	 * @version		: 1.0
	 * @description	: data to make a html form
	 */

	 include_once "library/facil3/core/modules/generic/FormView.class.php";

	 
	 
class ContentSelectView extends FormView{
	
	private $arrayContentVO;
	
	public $id;
	public $name;
	public $title;
	public $active;
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
	
	public $go_to_location;
	
	public $count_total = 0;
	
	public $image;
	public $file;

	
	

	/**
	 * @param $array_vo
	 * @desc recebe um array de contentVO
	 */
	public function __construct($arrayContentVO){
		parent::__construct();
		//parent::ignoreMethod(array('setImage','setFile'));	
			
		$this->arrayContentVO = $arrayContentVO;
	}

	public function gerate(){
		foreach($this->arrayContentVO as $ContentVO){
	
			$idValues[] = $ContentVO->getId();
			$activeValues[] = $ContentVO->getActive();
			$nameValues[] = $ContentVO->getName(Config::getLocale());
			$titleValues[] = $ContentVO->getTitle(Config::getLocale());
			$descriptionValues[] = $ContentVO->getDescription(Config::getLocale());
			$hatValues[] = $ContentVO->getHat(Config::getLocale());
			$contentValues[] = $ContentVO->getContent(Config::getLocale());
			$authorValues[] = $ContentVO->getAuthor(Config::getLocale());
			$templateUrlValues[] = $ContentVO->getTemplateUrl(Config::getLocale());
			$slugValues[] = $ContentVO->getSlug(Config::getLocale());
			$keyWordsValues[] = $ContentVO->getKeyWords(Config::getLocale());
			$dateValues[] = $ContentVO->getDate();
			$dateInValues[] = $ContentVO->getDateIn();
			$dateOutValues[] = $ContentVO->getDateOut();
			$orderValues[] = $ContentVO->getOrder();
					
			$this->count_total = $this->count_total+1;
			
			$this->setId(array("value"=>$idValues));
			
			$this->setActive(array("value"=>$activeValues));
			$this->setName(array("value"=>$nameValues));
			
			$this->setTitle(array("value"=>$titleValues));
			$this->setHat(array("value"=>$hatValues));
			$this->setDescription(array("value"=>$descriptionValues));
			$this->setContent(array("value"=>$contentValues));
			$this->setAuthor(array("value"=>$authorValues));
			$this->setSlug(array("value"=>$slugValues));
			$this->setTemplateUrl(array("value"=>$templateUrlValues));
			$this->setDate(array("value"=>$dateValues));
			$this->setDateIn(array("value"=>$dateInValues));
			$this->setDateOut(array("value"=>$dateOutValues));
			$this->setOrder(array("value"=>$orderValues));
			
		}
	}
	
//	public function setImage($args = array()){
//		$file = (count($this->contentVO->array_files)>0)?$this->contentVO->array_files[0]->url_file:"";
//		$defs = array(
//			'label'			=>	Translation::text('Image'),
//			'name'			=>	'image',
//			'visible'		=>	false,
//			'required'		=>	true,
//			'type'			=>	'image',
//			'value'			=>	array()
//		);
//		$data = array_merge($defs,$args);
//		$this->id = $data;
//	}
//	
//	public function setFile($args = array()){
//		$imagem = (count($this->contentVO->array_images)>0)?$this->contentVO->array_images[0]->url_image:"";
//		$defs = array(
//			'label'			=>	Translation::text('File'),
//			'name'			=>	'file',
//			'visible'		=>	false,
//			'required'		=>	true,
//			'type'			=>	'file',
//			'value'			=>	array()
//		);
//		$data = array_merge($defs,$args);
//		$this->id = $data;
//	}

	
	public function setId($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Id'),
			'name'			=>	'id',
			'visible'		=>	false,
			'required'		=>	true,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->id = $data;
	}
	
	public function setName($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Name'),
			'name'			=>	'name',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->name = $data;
	}
	
	public function setTitle($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Title'),
			'name'			=>	'title',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->title = $data;
	}
	
	public function setActive($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Active'),
			'name'			=>	'active',
			'visible'		=>	false,
			'required'		=>	false,
			'type'			=>	'checkbox',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->active = $data;
	}
	
	
	public function setHat($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Hat'),
			'name'			=>	'hat',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->hat = $data;
	}
	
	
	public function setDescription($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Description'),
			'name'			=>	'description',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'text',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->description = $data;
	}

	public function setContent($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Content'),
			'name'			=>	'content',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'htmlText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->content = $data;
	}
	
	
	public function setAuthor($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Author'),
			'name'			=>	'author',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->author = $data;
	}
	
	public function setGoToLocation($args = array()){
		$defs = array(
			'label'			=>	Translation::text('go to location'),
			'name'			=>	'go_to_location',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'url',
			'value'			=>	"",
		);
		$data = array_merge($defs,$args);
		$this->go_to_location = $data;
	}
	
	public function setTemplateUrl($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Template Url'),
			'name'			=>	'template_url',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->template_url = $data;
	}
	
	public function setSlug($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Slug'),
			'name'			=>	'slug',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->slug = $data;
	}
	
	
	public function setKeyWords($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Key words'),
			'name'			=>	'key_words',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->key_words = $data;
	}
	
	public function setDate($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Date'),
			'name'			=>	'date',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'date',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->date = $data;
	}

	public function setDateIn($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Date In'),
			'name'			=>	'date_in',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'date',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->date_in = $data;
	}
	
	public function setDateOut($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Date Out'),
			'name'			=>	'date_out',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'date',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->date_out = $data;
	}

	public function setOrder($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Order'),
			'name'			=>	'order',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	array(),
		);
		$data = array_merge($defs,$args);
		$this->order = $data;
	}
	
	
	public function getFormData(){
		
		$rtObj = new stdClass();
		$rtObj->fields 		= parent::getFieldData();
		$rtObj->count_total = $this->count_total;
		return $rtObj;
	}
	
}



