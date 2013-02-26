<?php

/**
	 * @author		: Alan Lucian M. Tormente
	 * @date		: 17/01/2011
	 * @version		: 1.0
	 * @description	: data to make a html form
	 */

	 include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
	 include_once "library/facil3/core/modules/generic/FormView.class.php";
	 include_once "library/facil3/core/modules/image/ImageFormView.class.php";
	 include_once "library/facil3/core/modules/file/FileFormView.class.php";
	 
	 
class ContentFormView extends FormView{
	
	private $formAction;
	
	private $contentVO;
	
	protected $id;
	protected $name;
	protected $title;
	protected $active;
	protected $hat;
	protected $description;
	protected $content;
	protected $author;
	protected $template_url;
	protected $slug;
	protected $key_words;
	protected $date;
	protected $date_in;
	protected $date_out;
	protected $order;
	
	protected $array_validation;
	
	protected $locale_to_get_info_vo = "pt_BR";
	public function setLocale($locale){
		$this->locale_to_get_info_vo = $locale;
	}
	/**
	 * @desc campo personalizado do form 
	 * @var array
	 */
	protected $personal_input;
	
	protected $image;
	protected $file;
	protected $category;
	
	

	public function __construct($vo, $action){
		parent::__construct();
		parent::ignoreMethod(array('setImage','setFile', 'setArrayVariable'));	

		$this->array_validation = array(
			'label'			=>	Translation::text('Array Validation'),
			'name'			=>	'array_validation[]',//agora em array
			'visible'		=>	false,//se tiver visible false já nem entra no type, deixar assim para não mostrar a categoria
			'required'		=>	false,
			'selected'		=>	array(),//array("name", "title", "hat", "description", "content", "date", "file", "image", "order", "date_in", "date_out"),
			'type'			=>	'multiCheckbox',
			'options'		=>	array(),//array("name", "title", "hat", "description", "content", "date"),
		);
				
		$this->contentVO = $vo;
		$this->formAction = $action;
	}
	
	public function setImage($imageFormView = NULL){
		if($imageFormView == NULL){
			$imageFormView = new ImageFormView();
		}
		$this->image = $imageFormView;
		if($imageFormView->getQuantity()>0){
//			echo Debug::li("dando push de image");
			$this->array_validation["selected"][] = "images";
		}
	}
	
	public function setFile($fileFormView = NULL){
		if(!$fileFormView){
			$fileFormView = new FileFormView();
		}
		$this->file = $fileFormView;
		if($fileFormView->getQuantity()>0){
//			echo Debug::li("dando push de files");
			$this->array_validation["selected"][] = "files";
		}
	}
	public function setId($args = array()){
		$defs = array(
			'name'			=>	'id',
			'visible'		=>	false,
			'required'		=>	true,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getId(),
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
			'value'			=>	$this->contentVO->getName($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->name = $data;
		if($this->name["visible"] == TRUE){
			$this->array_validation["selected"][] = "name";
//			echo Debug::li("dando push de name");
		}
	}
	
	public function setTitle($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Title'),
			'name'			=>	'title',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getTitle($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->title = $data;
		if($this->title["visible"] == TRUE){
			$this->array_validation["selected"][] = "title";
//			echo Debug::li("dando push de tittle");
		}
	}
	
	public function setActive($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Active'),
			'name'			=>	'active',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'checkbox',
			'value'			=>	$this->contentVO->getActive($this->locale_to_get_info_vo),
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
			'value'			=>	$this->contentVO->getHat($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->hat = $data;
		if($this->hat["visible"] == TRUE){
			$this->array_validation["selected"][] = "hat";
//			$this->array_validation["option"][] = "hat";
//			echo Debug::li("dando push de hat");
		}
	}
	
	
	public function setDescription($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Description'),
			'name'			=>	'description',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'text',
			'value'			=>	$this->contentVO->getDescription($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->description = $data;
		if($this->description["visible"] == TRUE){
			$this->array_validation["selected"][] = "description";
//			$this->array_validation["option"][] = "description";
//			echo Debug::li("dando push de description");
		}
	}
	/**
	 * @param $args array 'label'			=>	Translation::text('Content'),
			'name'			=>	'content',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'htmlText',
			'value'			=>	$this->contentVO->getContent(),
	 * @return void
	 */
	public function setContent($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Content'),
			'name'			=>	'content',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'htmlText',
			'value'			=>	$this->contentVO->getContent($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->content = $data;
		if($this->content["visible"] == TRUE){
			$this->array_validation["selected"][] = "content";
//			$this->array_validation["option"][] = "content";
		}
	}
	
	
	public function setAuthor($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Author'),
			'name'			=>	'author',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getAuthor($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->author = $data;
		if($this->author["visible"] == TRUE){
			$this->array_validation["selected"][] = "author";
//			$this->array_validation["option"][] = "author";
		}
	}
	
	
	public function setTemplateUrl($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Template Url'),
			'name'			=>	'template_url',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getTemplateUrl($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->template_url = $data;
		if($this->template_url["visible"] == TRUE){
			$this->array_validation["selected"][] = "template_url";
//			$this->array_validation["option"][] = "template_url";
		}
	}
	
	public function setSlug($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Slug'),
			'name'			=>	'slug',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getSlug($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->slug = $data;
		if($this->slug["visible"] == TRUE){
			$this->array_validation["selected"][] = "slug";
		}
	}
	
	
	public function setKeyWords($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Key words'),
			'name'			=>	'key_words',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getKeyWords($this->locale_to_get_info_vo),
		);
		$data = array_merge($defs,$args);
		$this->key_words = $data;
		if($this->key_words["visible"] == TRUE){
			$this->array_validation["selected"][] = "key_words";
		}
	}
	
	public function setDate($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Date'),
			'name'			=>	'date',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getDate(),
		);
		$data = array_merge($defs,$args);
		$this->date = $data;
		if($this->date["visible"] == TRUE){
			$this->array_validation["selected"][] = "date";
		}
	}

	public function setDateIn($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Date In'),
			'name'			=>	'date_in',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getDateIn(),
		);
		$data = array_merge($defs,$args);
		$this->date_in = $data;
		if($this->date_in["visible"] == TRUE){
			$this->array_validation["selected"][] = "date_in";
		}
	}
	
	public function setDateOut($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Date Out'),
			'name'			=>	'date_out',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getDateOut(),
		);
		$data = array_merge($defs,$args);
		$this->date_out = $data;
		if($this->date_out["visible"] == TRUE){
			$this->array_validation["selected"][] = "date_out";
		}
	}

	public function setOrder($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Order'),
			'name'			=>	'order',
			'visible'		=>	true,
			'required'		=>	false,
			'type'			=>	'simpleText',
			'value'			=>	$this->contentVO->getOrder(),
		);
		$data = array_merge($defs,$args);
		$this->order = $data;
		if($this->order["visible"] == TRUE){
			$this->array_validation["selected"][] = "order";
		}
	}
	
	/**
	 * @desc os campos passados serão validados
	 * @param array("name", "title", "hat", "description", "content", "date"),
	 * @return void
	 */
	public function setArrayValidation($args = array()){
		$defs = array(
			'label'			=>	Translation::text('Array Validation'),
			'name'			=>	'array_validation[]',//agora em array
			'visible'		=>	false,//se tiver visible false já nem entra no type, deixar assim para não mostrar a categoria
			'required'		=>	false,
			'selected'		=>	array(),//array("name", "title", "hat", "description", "content", "date", "file", "image", "order", "date_in", "date_out"),
			'type'			=>	'multiCheckbox',
			'options'		=>	array(),//array("name", "title", "hat", "description", "content", "date"),
		);
		$data = array_merge($defs,$args);		
		$this->array_validation = $data;
	}
	
	public function setCategory($args = array()){
		
		$CategoryDAO = CategoryDAO::getInstance();
		$tempCategories = $CategoryDAO->select( CategoryDAO::RETURN_VO ,NULL, 1);
		$categories = array();
		if($tempCategories->success){
			foreach($tempCategories->result as $categoryVO){
				$categories[] = $categoryVO->toStdClass($this->locale_to_get_info_vo);
			}
		}
		if(FALSE){
			$this->contentVO = new ContentVO();			
		}
		
		$link_categoryVO = $this->contentVO->getWhoILinked("category", 1);
		
		
		$array_selected_category = array();
		foreach ($link_categoryVO as $linkVO){
			// $categoryVO = $linkVO->getLinkedVO();
			// var_dump($categoryVO);die;
			$array_selected_category[] =  $linkVO->getTableId(); //; 
		}
		
		
		$defs = array(
			'label'			=>	Translation::text('Category'),
			'name'			=>	'category',//agora em array
			'visible'		=>	false,//se tiver visible false já nem entra no type, deixar assim para não mostrar a categoria
			'required'		=>	false,
			'selected'		=>	$array_selected_category,
			'type'			=>	'multiCheckbox',
			'options'		=>	$categories,
		);
		$data = array_merge($defs,$args);
		
		$this->category = $data;
		// $this->contentVO->getLinks();
		
	} 
	
	
	/**
	 * @desc o campo option do primeiro parametro ter os campos name e id
	 * @param $args ("option"=>array("name"=>"nome", "id"=>"1"))
	 * @param $array_selected_category
	 * @return void
	 */
	public function setPersonalInput($args = array()){
		$CategoryDAO = CategoryDAO::getInstance();
		$tempCategories = $CategoryDAO->select( CategoryDAO::RETURN_VO ,NULL, 1);

		$link_categoryVO = $this->contentVO->getWhoILinked("category", 1);
		
		$array_selected_category = array();
		foreach ($link_categoryVO as $linkVO){
			// $categoryVO = $linkVO->getLinkedVO();
			// var_dump($categoryVO);die;
			$array_selected_category[] =  $linkVO->getTableId(); //; 
		}

		
		$Options = array();
		if(DataHandler::getValueByArrayIndex($args, "options")){
			//converte para objeto
			$tempOption = DataHandler::getValueByArrayIndex($args, "options");
			$Options[] = (object) $tempOption;
		}
	
		$defs = array(
			'label'			=>	"",
			'name'			=>	'personal',//agora em array
			'visible'		=>	true,//se tiver visible false já nem entra no type, deixar assim para não mostrar a categoria
			'required'		=>	false,
			'selected'		=>	$array_selected_category,
			'type'			=>	'multiCheckbox',
			'options'		=>	$Options
		);
		
		$data = array_merge($defs, $args);
		//Debug::print_r($data);
		$this->personal_input = $data;
		
	} 
	public function translateMessage($return_message){
		$label_name = $return_message;
		switch($return_message){
			case "images":
				return "É obriatório o envio de imagem para este conteúdo.";
				break;
			case "files":
				return "É obriatório o envio de arquivo para este conteúdo.";
				break;
			default:
				switch($return_message){
					case "name":
						$label_name = $this->name["label"];
						break;
					case "title":
						$label_name = $this->title["label"];
						break;
					case "active":
						$label_name = $this->active["label"];
						break;
					case "hat":
						$label_name = $this->hat["label"];
						break;
					case "description":
						$label_name = $this->description["label"];
						break;
					case "content":
						$label_name = $this->content["label"];
						break;
					case "author":
						$label_name = $this->author["label"];
						break;
					case "template_url":
						$label_name = $this->template_url["label"];
						break;
					case "slug":
						$label_name = $this->slug["label"];
						break;
					case "key_words":
						$label_name = $this->key_words["label"];
						break;
					case "date":
						$label_name = $this->date["label"];
						break;
					case "date_in":
						$label_name = $this->date_in["label"];
						break;
					case "date_out":
						$label_name = $this->date_out["label"];
						break;
				}
				$label_name = "O campo \"".$label_name."\" é obrigatório.";
				break;
		}
		return $label_name;
	}
	public function getFormData(){
		
		$rtObj = new stdClass();
		$rtObj->fields 	= parent::getFieldData();
		//para formar a array doida
		//Debug::print_r($this->array_validation);
		$temp = (object) $this->array_validation;
		$rtObj->fields->array_validation = $temp;
		//exit();
		if($this->image == NULL)
			$this->setImage();
		
		if($this->file == NULL)
			$this->setFile();
			
		$rtObj->modules = array('image'=> $this->image, 'file'=>$this->file);
		$rtObj->action = $this->formAction;
		
		return $rtObj;
	}
	
}



