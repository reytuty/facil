<?php
include_once "library/facil3/core/vo/LinkVO.class.php";
/**
 Pra que serve essa VO?
  			Resposta:
 				Para configurar o que a FacilContent precisa cadastrar no banco de maneira abstrata, alheia a
 				um projeto específico.
 Como usar?
 			Resposta:
 				Crie uma controler, extenda a FacilContent e sobreescreva os metodos. Configure essa VO com os dados e 
 				chame parent::$metodo
 				Ou então inicie uma FacilContent, e utilize seus metodos.
 */
/**
 * VO de apoio da controller FacilContent
 * @author Mauricio Amorim
 *
 */
/**
 * @author 	Renato Miawaki
 * @version 1.1
 * @desc	Modifiquei para, no caso da content, ela poder ser vinculada em mais de uma entidade, podendo adicionar N links
 * 			Obs.: Linkar significa que esse conteúdo será vinculado a entidade, e nao que algo será vinculado a esse contepudo
 */
class ContentInfoPostVO{
	//campos da tabela, atributos
	public $array_request;
//	public $id;
//	public $active;
//	public $name;
//	public $title;
//	public $hat;
//	public $description;
//	public $content;
//	public $author;
//	public $template_url;
//	public $slug;
//	public $key_words;
//	public $date;
//	public $date_in;
//	public $date_out;
//	public $order;
	//infos de requisição de content
	/**
	 * @var array de LinkVO
	 */
	private $request_links_to_add = array();
	public function addLink($table_to_link, $table_id_to_link){
		$LinkVO = new LinkVO(NULL, "content", DataHandler::getValueByArrayIndex($array_request, "id"), $table_to_link, $table_id_to_link, 1);
		//adiciona esse LinkVO para depois pegar o linked table, linked table id e também o active, só por isso
		$this->request_links_to_add[] = $LinkVO;
	}
	/**
	 * @var int or NULL passe NULL para novo cadastro e passe o int para update
	 */
	public $request_content_id;
	public $request_slug;
	/**
  	 * @var string $request_locale
  	 */
	public $request_locale = NULL;
	public $request_quant_start = NULL;
	public $request_quant_limit = NULL;
	public $request_type_of_return;
	
	public function __construct($array_fetch = NULL){
		if($array_fetch != NULL && is_array($array_fetch)){
			$this->setFetchArray($array_fetch);
		}
	}
	public function setFetchArray($array){
		//
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