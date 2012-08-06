<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");

include_once(Config::FOLDER_APPLICATION."modules/theme/dao/ThemeDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/theme/vo/ThemeVO.class.php");

include_once(Config::FOLDER_APPLICATION."modules/type/dao/TypeDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/type/vo/TypeVO.class.php");

include_once(Config::FOLDER_APPLICATION."modules/style/dao/StyleDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/style/vo/StyleVO.class.php");

include_once(Config::FOLDER_APPLICATION."modules/technique/dao/TechniqueDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/technique/vo/TechniqueVO.class.php");

include_once(Config::FOLDER_APPLICATION."modules/format/dao/FormatDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/format/vo/FormatVO.class.php");

include_once(Config::FOLDER_APPLICATION."modules/composition/dao/CompositionDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/composition/vo/CompositionVO.class.php");

//include_once(Config::FOLDER_APPLICATION."modules/material/dao/MaterialDAO.class.php");
//include_once(Config::FOLDER_APPLICATION."modules/material/vo/MaterialVO.class.php");

//include_once(Config::FOLDER_APPLICATION."modules/color/dao/ColorDAO.class.php");
//include_once(Config::FOLDER_APPLICATION."modules/color/vo/ColorVO.class.php");


include_once("library/facil3/core/controller/interface/HTTPControllerInterface.class.php");
include_once "library/facil3/core/http/HttpAdminGenericResult.class.php";

include_once(Config::FOLDER_APPLICATION."controller/Admin.php");
class Attribute extends Admin implements HTTPControllerInterface{
	private $arrayRestFolder 	= array();
	private $arrayVariable 		= array();

	private $DAO;
	
	private $module;
	
	public function __construct($arrayRestFolder = NULL){
		parent::__construct($arrayRestFolder);
		if($arrayRestFolder != NULL){
			$this->arrayRestFolder = $arrayRestFolder;
			$this->arrayVariable   = Navigation::getVariableArraySlug($this->arrayRestFolder);
		}
		
		$this->module = (DataHandler::getValueByArrayIndex($this->arrayVariable, "module"))?DataHandler::getValueByArrayIndex($this->arrayVariable, "module"):"theme";
		switch($this->module){
			case "style":
				$this->DAO = StyleDAO::getInstance();
				break;
			case "technique":
				$this->DAO = TechniqueDAO::getInstance();
				break;
			case "type":
				$this->DAO = TypeDAO::getInstance();
				break;
			case "format":
				$this->DAO = FormatDAO::getInstance();
				break;
			case "composition":
				$this->DAO = CompositionDAO::getInstance();
				break;
			case "theme":
				$this->DAO = ThemeDAO::getInstance();
				break;
			default:
				$this->DAO = ThemeDAO::getInstance();
				break;
		}
	}
	/* 
	 * @return lista de usuarios ativos
	 */
	public function init(){
		$page = 1;
		$limit_page = 21;
		$quant_start = 0;
		
		foreach($this->arrayVariable as $variable => $value){
			if($variable == "pag" && DataHandler::forceInt($value) > 0){
				$page = DataHandler::forceInt($value);
			}
		}
		$quant_start = $page * $limit_page - $limit_page;
		
		//iniciando o retorno padrao em http result
		$returnResult = new HttpResult();
		$arrayTheme = array();
		//fazendo o que precisa fazer para ter os dados
		$ReturnDataVO = $this->DAO->select(AttributeBaseDAO::RETURN_VO, NUll, 1, NUll, NULL, $limit_page, $quant_start);
//		Debug::print_r($ReturnDataVO);
		//setando sucesso true caso tenha dado certo
		$returnResult->setSuccess((count($ReturnDataVO->result) > 0));
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		$retornoDaPaginaHTML->arrayVariable = $this->arrayVariable;
		$retornoDaPaginaHTML->arrayAttributes = $ReturnDataVO->result;
		$retornoDaPaginaHTML->module = $this->module;
		$retornoDaPaginaHTML->page = $page;
		$retornoDaPaginaHTML->limit_page = $limit_page;
		$retornoDaPaginaHTML->count_total = $ReturnDataVO->count_total;
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	/**
	 * @return HttpResult
	 */
	public function insert(){
//		echo $this->module;
		$returnResult = new HttpResult();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		
		$VO = $this->DAO->getVO();
		$VO->setFetchArray($_POST);
		if($_POST){
			$VO->setActive(1);
			$ReturnResultVO = $VO->commit(TRUE);
//			Debug::print_r($ReturnResultVO);exit();
			if($ReturnResultVO->success){
				Navigation::redirect("admin/attribute/module.".$this->module);
				exit();
			}
			$retornoDaPaginaHTML->return_result_vo = $ReturnResultVO;
		}
		$retornoDaPaginaHTML->attribute_vo = $VO;
		$retornoDaPaginaHTML->module = $this->module;
		//iniciando o retorno padrao em http result
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	public function update(){
		$ReturnResultVO = new ReturnResultVO();
		$retornoDaPaginaHTML = new HttpAdminGenericResult();
		//iniciando o retorno padrao 
		$returnResult = new HttpResult();
		
		$id = 0;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");			
		}
		
		$VO = $this->DAO->getVO();
		$VO->setId($id, TRUE);
		if($_POST){
			$VO->setFetchArray($_POST);
			$VO->setId($id);
			$ReturnResultVO = $VO->commit(TRUE);
//			Debug::print_r($ReturnResultVO);exit();
			if($ReturnResultVO->success){
				Navigation::redirect("admin/attribute/module.".$this->module);
				exit();
			}
		}
		$retornoDaPaginaHTML->return_result_vo = $ReturnResultVO;
		$retornoDaPaginaHTML->attribute_vo = $VO;
		$retornoDaPaginaHTML->module = $this->module;
		//iniciando o retorno padrao em http result
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		return $returnResult;
	}
	
	
	public function delete(){
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->success = FALSE;
		if(DataHandler::getValueByArrayIndex($this->arrayVariable, "id")){
			$id = DataHandler::getValueByArrayIndex($this->arrayVariable, "id");			
			$ReturnDataVO = $this->DAO->delete($id);
			$ReturnResultVO->success	 	= $ReturnDataVO->success;
			$ReturnResultVO->result			= $ReturnDataVO->result;
		}
		echo $ReturnResultVO->toJson();
		exit();
	}

}