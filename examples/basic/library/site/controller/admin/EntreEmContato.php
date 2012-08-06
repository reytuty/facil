<?php
include_once "library/".Config::FOLDER_APPLICATION."/controller/admin/default/Content.php";
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once "library/".Config::FOLDER_APPLICATION."/modules/content/vo/ContentEncVO.class.php";
/**
 * @author 			: Renato Miawaki
 * @desc			: para admin category_id 12
 * @date			: 24/01/2011
 */



class EntreEmContato extends Content{
	protected $category_id 	= 3;
	private $my_action 		= "admin/entre_em_contato/commit";
	private $my_redirect 	= "admin/entre_em_contato/edit";
	/**
	 * @param $arrayRest
	 * @return void
	 */
	public function __construct($arrayRest = NULL){
		parent::__construct($arrayRest);
	}
	public function init(){
		$this->edit();
	}
	/**
	 * @return para poder inserir
	 */
	public function insert(){
		$this->edit();
	}
	public function edit(){
		//busca o primeiro content vinculada com essa categoria
		if($this->category_id > 0){
			$CategoryVO = new CategoryVO();
			$CategoryVO->setId($this->category_id, TRUE);
			$array_content = $CategoryVO->getLinks("content", 1);
			//se tiver mais de um link
			if(count($array_content) > 0){
				//pega o primeiro pois aqui sÃ³ importa 1.
				$LinkVO = $array_content[0];
				if(FALSE){
					//ajuda aptana
					$LinkVO = new LinkVO();
				}
				//echo Debug::li("tem content vinculada com id:".$LinkVO->getLinkedTableId());
				//inicia a ContentEncVO
				$this->ContentEncVO->setId($LinkVO->getLinkedTableId(), TRUE);
			}
		}
		//criar o formView
		$formData = new ContentFormView($this->ContentEncVO, Config::getRootPath($this->my_action));
		//trocando o rÃ³tulo para Content
		$formData->setContent(array("label"=>"Edite o texto:"));
		
		$ImageFormView = new ImageFormView();
		$ImageFormView->setFormLabel("Inserir Imagem");
		//quando ta em outro idioma que nÃ£o o padrao, nÃ£o pode ter imagem
		$ImageFormView->setQuantity(0);

		$FileFormView = new FileFormView();
		$FileFormView->setFormLabel("Inserir Arquivo");
		$FileFormView->setQuantity(0);
		

		
		if($this->ContentEncVO->getId() > 0){
			//se tiver id significa que o content jÃ¡ foi adicionado alguma vez, entÃ£o deixa queto
		} else {
			//seta o id dessa categoria caso nÃ£o tenha nenhum content vinculado, pois essa categoria sÃ³ admite 1 content
			$formData->setCategory(array("selected"=>array($this->category_id)));
		}
		
		$formData->setImage($ImageFormView);
		$formData->setFile($FileFormView);
		$formData->setMassiveAttr('visible', FALSE, array(
														'Name',
														'Title',
														'Hat',
														'Description',
														//'Content',
														'Author',
														'TemplateUrl',
														'Slug',
														'KeyWords',
														'Date',
														'DateIn',
														'DateOut',
														'Order'
														));
		
		$formData->setSlug(array("value"=>"entre_em_contato", "visible"=>FALSE));								
		$formData->setActive(array("value"=>"1", "visible"=>FALSE));								
		
		
		parent::edit($formData, TRUE, NULL, $this->my_redirect);		
	}
	
}