<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 15/07/2010
	 * @version		: 1.0
	 * @tabela		: tabela category
	 * @slug		: 
	 * 					A tabela category
	 *  
	  						id, 
	  						active, 
	  						category_id,
	  						name,
	  						slug,
	  						order
	 *
	 * @obs			: utiliza a tabela parametro para designar o idioma (tipo:label_ptbr)
	 */
//importa classes de apoio
include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
include_once "library/facil3/interface/vo/PatternVO.class.php";
include_once "library/facil3/core/vo/BaseVO.class.php";
include_once "library/facil3/core/DbInterface.class.php";

class CategoryVO extends BaseVO implements PatternVO{
	
  	public $category_id;
  	public $name;
  	public $slug;
  	public $order; 

  	public $ContentDAO;

  	public $array_category 	= array();
  	public $array_image 	= array();
  	
 	const CATEGORY_NAME 			= "name";
 	const CATEGORY_SLUG 			= "slug";
 	
  	function __construct($arrayFetchPost = NULL){
		//inicia funo de validacao
		parent::_construct($this);
		$this->__table = "category";
		//se enviar $arrayFetchPost é para distrinchar internamente os dados do usuario
		if($arrayFetchPost !== NULL){
			//enviou array para distrinchar
			$this->setFetchArray($arrayFetchPost);
		}
	}//end construct
	/**
	 * inicia internamente a DAO
	 * @return void
	 */
	public function startDAO(){
		$this->DAO = CategoryDAO::getInstance();
	}
	/**
	 * precisa enviar uma array dentro dos padr�es dessa VO, pode enviar diretamente o $_POST
	 * @param (array) $array_dados
	 * @return void
	 */
	function setFetchArray($array_dados){
		$this->id = DataHandler::getValueByArrayIndex($array_dados, "id");
		$this->category_id = DataHandler::getValueByArrayIndex($array_dados, "category_id");
		$this->active = DataHandler::getValueByArrayIndex($array_dados, "active");
		$this->name = DataHandler::getValueByArrayIndex($array_dados, "name");
		$this->slug = DataHandler::getValueByArrayIndex($array_dados, "slug");
		$this->order = DataHandler::getValueByArrayIndex($array_dados, "order"); 
	}
	
	/**
	 * @param (object) $obj_dados
	 * @return void
	 */
	function setFetchObject($obj_dados){
		$this->id = DataHandler::getValueByStdObjectIndex($obj_dados, "id");
		$this->category_id = DataHandler::getValueByStdObjectIndex($obj_dados, "category_id");
		$this->active = DataHandler::getValueByStdObjectIndex($obj_dados, "active");
		$this->name = DataHandler::getValueByStdObjectIndex($obj_dados, "name");
		$this->slug = DataHandler::getValueByStdObjectIndex($obj_dados, "slug");
		$this->order = DataHandler::getValueByStdObjectIndex($obj_dados, "order"); 
	}
	
	//---------------------------------------------------------  SETs
	
	/**
	 * @param $category_id (int)
	 * @return void
	 */
	public function setCategoryId($category_id){
		if($category_id !== NULL){
			$this->category_id = DataHandler::forceInt($category_id);
		}
	}
	
	/**
	 * @param $name
	 * @param $locale
	 * @return void
	 */
	public function setName($name, $locale = NULL){
		if($locale == NULL){
			$this->name = DataHandler::forceString($name);
		} else {
			$this->addTherms(CategoryVO::CATEGORY_NAME, $locale, $name);
		}
	}
	/**
	 * @param $slug (str)
	 * @param $locale
	 * @return void
	 */
	public function setSlug($slug, $locale = NULL){
		if($locale == NULL){
			$this->slug = DataHandler::forceString($slug);
		} else {
			$this->addTherms(CategoryVO::CATEGORY_SLUG, $locale, $slug);
		}
	}
	/**
	 * @param $order (int)
	 * @return void
	 */
	public function setOrder($order){
		$this->order = DataHandler::forceInt($order);
	}
	// ------------------------------------------------------   GETs
	/**
	 * @return $category_id (int)
	 */
	public function getCategoryId(){
		return $this->category_id;
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getName($locale = NULL){
		if($locale == NULL){
			return $this->name;
		} else {
			$tempResult = $this->getStringTherm(CategoryVO::CATEGORY_NAME, $locale);
			//echo $tempResult;
			return ($tempResult == "")?$this->name:$tempResult;
		}
	}
	/**
	 * @param $locale
	 * @return str
	 */
	public function getSlug($locale){
		if($locale == NULL){
			return $this->slug;
		} else {
			$tempResult = $this->getStringTherm(CategoryVO::CATEGORY_SLUG, $locale);
			return ($tempResult == "")?$this->slug:$tempResult;
		}
	}
	/**
	 * @return $order (int)
	 */
	public function getOrder(){
		return $this->order;
	}
	/**
	 * Validação da VO para ver se está apta a ser cadastrada
	 * @return array
	 */
	function validate(){
		//vai validar a VO inteira conforme as regras
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = TRUE;
		if(!strlen($this->getName()) > 4){
			$ReturnResultVO->success = FALSE;
			$ReturnResultVO->addMessage(Translation::text("LibraryLanguage::ERROR_CATEGORY_VALIDATE_NAME"));
		}
		return $ReturnResultVO;
	}
	
	/**
	 * @param int $tempResult
	 * @param array $arrayReturn
	 */
	public function resultHandler($tempResult, &$arrayReturn){
		switch($tempResult){
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_ID:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CATEGORY_UPDATE_DONOT_HAVE_ID");
				break;
			case DbInterface::ERROR_UPDATE_DONOT_HAVE_UPDATES:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CATEGORY_UPDATE_DONOT_HAVE_UPDATE");
				break;
			case DbInterface::ERROR_UPDATE_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CATEGORY_UPDATE_COMMIT");
				break;
			case DbInterface::ERROR_INSERT_COMMIT:
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CATEGORY_INSERT_COMMIT");
				break;
			case DbInterface::ERROR_DUPLICATE_ENTRY;
				$arrayReturn[] = Translation::text("LibraryLanguage::ERROR_CATEGORY_DUPLICATE_ENTRY");
			case DbInterface::SUCCESS:
				$arrayReturn[] = "categoria cadastrada";
				break;
			default:
				$arrayReturn[] = "erro sinistro($tempResult).";
				break;
		}//end switch
	}

	/**
	 * @param $typeOfReturn
	 * @param $active
	 * @return array_category
	 * @desc atenção pedido retorno em std_class os dados não voltaram traduzidos pelo locale 
	 */
	public function selectCascade($typeOfReturn = CategoryDAO::RETURN_STD_OBJECT, $active = NULL){
		$this->DAO = CategoryDAO::getInstance();
		if($this->array_category == NULL || $force){
			//busca categorias filhas dessa
			$array_category = $this->DAO->selectCascade($typeOfReturn, $this->id, $active);
			if($array_category->success){
				//print_r($array_product->result);
				foreach($array_category->result as $array_category_std){
					$this->array_category[] = $array_category_std;
				}
			}
		}
		return $this->array_category;
	}
}