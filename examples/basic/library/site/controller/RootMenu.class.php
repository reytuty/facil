<?php
include_once("library/facil3/navigation/http/HttpResult.class.php");
include_once(Config::FOLDER_APPLICATION."modules/material/dao/MaterialDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/color/dao/ColorDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/style/dao/StyleDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/format/dao/FormatDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/theme/dao/ThemeDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/composition/dao/CompositionDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/technique/dao/TechniqueDAO.class.php");
include_once(Config::FOLDER_APPLICATION."modules/type/dao/TypeDAO.class.php");
include_once Config::FOLDER_APPLICATION."modules/product_model/dao/ProductModelQuadroDAO.class.php";
include_once "library/facil3/navigation/vo/BreadCrumbInfoVO.class.php";
include_once(DataHandler::removeDobleBars(Config::getFolderView()."/HttpMenu.class.php"));

/**
 * @author 	Renato Miawaki
 * @desc	Controller central, se nenhuma outra controller for encontrada, esta controller é iniciada
 * 			init é chamada caso nenhum metodo que combine com a requisição seja encontrada
 */
class RootMenu{
	private $MaterialDAO;
	private $ColorDAO;
	private $StyleDAO;
	private $FormatDAO;
	private $ThemeDAO;
	private $CompositionDAO;
	private $TechniqueDAO;
	private $TypeDAO;
	
	//array de Ojetos std
	private $arrayMaterial;
	private $arrayColor;
	private $arrayStyle;
	private $arrayFormat;
	private $arrayTheme;
	private $arrayComposition;
	private $arrayTechnique;
	private $arrayType;
	
	//id dos respectivos tipos
	private $requestMaterial;
	private $requestColor;
	private $requestStyle;
	private $requestFormat;
	private $requestTheme;
	private $requestComposition;
	private $requestTechnique;
	private $requestType;
	private $requestResumeSearch;
	private $requestPromocao;
	//utilizado para busca por string
	private $requestSearch = NULL;
	
	private $arrayRestFolder = array();
	private $arrayVariable = array();
	
	private $arrayBreadCrumb = array();
	private $currentQuery = NULL;
	
	public function __construct($currentQuery = null){
		$this->currentQuery = $currentQuery;	
		//Config::getConection();
		$this->arrayRestFolder 	= Navigation::getURI(Config::getRootPath(), Navigation::URI_RETURN_TYPE_ARRAY);
		$this->arrayVariable 	= Navigation::getVariableArraySlug($this->arrayRestFolder);
		if(in_array("promocoes", $this->arrayRestFolder)){
			$this->arrayVariable["promocoes"] = 1;
		}
	}
	
	private function consultValues(){
		$ProductModelQuadroDAO = ProductModelQuadroDAO::getInstance();
		$this->MaterialDAO 		= MaterialDAO::getInstance(); 
		$this->ColorDAO 		= ColorDAO::getInstance(); 
		$this->StyleDAO 		= StyleDAO::getInstance(); 
		$this->FormatDAO 		= FormatDAO::getInstance(); 
		$this->ThemeDAO 		= ThemeDAO::getInstance(); 
		$this->CompositionDAO 	= CompositionDAO::getInstance(); 
		$this->TechniqueDAO 	= TechniqueDAO::getInstance(); 
		$this->TypeDAO 			= TypeDAO::getInstance(); 
		
		//fazendo o que precisa fazer para ter os dados do menu
		$this->arrayMaterial = $this->MaterialDAO->select(MaterialDAO::RETURN_VO, NULL, 1);
		
		$this->addTotal($this->arrayMaterial);
		
		$this->arrayColor = $this->ColorDAO->select(ColorDAO::RETURN_VO, NULL, 1);
		// $this->addTotal($this->arrayColor);
		//Debug::print_r($this->arrayColor);
		//busca as imagens
		if($this->arrayColor->success){
			$tempVO = array();
			foreach($this->arrayColor->result as $ColorVO){
				$tempStdColor = $ColorVO->toStdClass();
				//primeira letra maiuscula
				$tempStdColor->name = ucfirst($tempStdColor->name);
				$tempVO[] = $tempStdColor;
				//Debug::print_r($tempVO);
			}
			$this->arrayColor->result = $tempVO;
		}
		$this->arrayStyle = $this->StyleDAO->select(StyleDAO::RETURN_VO, NULL, 1);
		$this->addTotal($this->arrayStyle);
		
		$this->arrayFormat = $this->FormatDAO->select(FormatDAO::RETURN_VO, NULL, 1);
		$this->addTotal($this->arrayFormat);
		
		
		$this->arrayTheme = $this->ThemeDAO->select(ThemeDAO::RETURN_VO, NULL, 1);
		$this->addTotal($this->arrayTheme);
		
		$this->arrayComposition = $this->CompositionDAO->select(CompositionDAO::RETURN_VO, NULL, 1);
		$this->addTotal($this->arrayComposition);
		
		$this->arrayTechnique = $this->TechniqueDAO->select(TechniqueDAO::RETURN_VO, NULL, 1);
		$this->addTotal($this->arrayTechnique);
		
		$this->arrayType = $this->TypeDAO->select(TypeDAO::RETURN_VO, NULL, 1);
		$this->addTotal($this->arrayType);
		

		//define a base da url
		$this->url_final = Config::getRootPath("quadros/filter");
		$this->arrayBreadCrumb = array();
		if($this->arrayVariable != NULL){
			$temp_array = array_splice($this->arrayRestFolder, 2, count($this->arrayRestFolder)-1);
			//Debug::print_r($temp_array);
			//exit();
			if($this->arrayRestFolder[1] == "filter"){
				$this->url_final .= "/".implode("/" , $temp_array);
				//tirando a página da url final
				$this->url_final = preg_replace("/\/pag\.[0-9]+?/", "", $this->url_final);
			}
			$array_relacao_variavel_resultado = array();
			$array_relacao_variavel_resultado["search"] 		= "requestSearch";
			$array_relacao_variavel_resultado["material"] 		= "requestMaterial";
			$array_relacao_variavel_resultado["color"] 			= "requestColor";
			$array_relacao_variavel_resultado["style"] 			= "requestStyle";
			$array_relacao_variavel_resultado["format"] 		= "requestFormat";
			$array_relacao_variavel_resultado["theme"] 			= "requestTheme";
			$array_relacao_variavel_resultado["composition"] 	= "requestComposition";
			$array_relacao_variavel_resultado["technique"] 		= "requestTechnique";
			$array_relacao_variavel_resultado["type"] 			= "requestType";
			$array_relacao_variavel_resultado["promocoes"] 		= "requestPromocao";
			
				foreach($this->arrayVariable as $variable => $value){
					if($variable == "search" && DataHandler::forceString($value) != NULL){
						$this->requestResumeSearch = DataHandler::forceString(urldecode($value));
						if(strlen($this->requestResumeSearch) > 10){
							$this->requestResumeSearch = substr($this->requestResumeSearch, 0, 10)."...";
						} 
					}
					if(DataHandler::forceInt($value) > 0){
						if(isset($array_relacao_variavel_resultado[$variable]) && $array_relacao_variavel_resultado[$variable] != null){
							$this->$array_relacao_variavel_resultado[$variable] = DataHandler::forceInt(trim($value));
						}
					} else {
						//se não nenhum desses itens segnifica q é sujeira por isso não grava na url
						continue;
					}
					//popula o bread crumb de maneira incompleta pois seria esforço a toa procurar o título aqui
					$this->arrayBreadCrumb[] = new BreadCrumbInfoVO("", $variable, $value);
					//concatena as veriaveis ja enviadas para fazer a base do link
					//$this->url_final .= "/".$variable.".".$value;
				}
		}
	}
	
	private function addTotal(&$VOdata){
		if(!$this->currentQuery)
			$this->currentQuery = "SELECT count( DISTINCT `view_product_model_quadro_b`.id) as count FROM `view_product_model_quadro_b` WHERE 1 AND view_product_model_quadro_b.`active` = '1'";
			//oinc		
		$dbi = new DbInterface();
		foreach ($VOdata->result as &$result){
			if($this->currentQuery){
				$query = $this->currentQuery;	
				if($result->__table == 'material'){
					
					$query  = str_replace("\n", '', $query);
					
					// $query = str_replace('count(', 'count( DISTINCT', strtolower($query));
					if(!preg_match( '/(inner join([^a-z])+view_product_quadro)/mi', strtolower($query)) ){
						$join =  'INNER JOIN
										view_product_quadro
											ON `view_product_quadro`.product_model_id = `view_product_model_quadro_b`.id
								  where
								';	
						$query = str_replace('where', $join, strtolower($query));
					}
					$query.=  " AND view_product_quadro.active = 1 AND view_product_quadro.`material_id` = '" . $result->id . "'  ";
					
					$query_count = $query;
				}else{
					$query_count = $query . ' AND ' .  str_replace('product_model_', '', $result->__table )  . "_id = " . $result->id;	
				}
				
				//o cache não está gravado
				$rt = $dbi->query($query_count);
				//echo Debug::li($this->currentQuery);
				$rt->fetchAll();
				
				$result->total = $rt->result[0]->count;
				
				$result->toStdClass();   
			}
		}
		
	}
	
	public function init(){
		$returnResult = new HttpResult();
		$returnResult->setSuccess(1);
		
		//iniciando o resultado para o html
		$retornoDaPaginaHTML = new HttpMenu();
		//inicia a variavel de consulta de obj para cache
		$obj = NULL;
		$save_cache = TRUE;
		$cache_base_folder = "cache/menu/init/";
		if(Config::USE_QUERY_CACHE){
			//verifica se tem cache
			$cache_folder 	= $cache_base_folder;
			$cache_file 	= md5(implode("/", $this->arrayRestFolder));
			/*
			Debug::print_r($this->arrayRestFolder);
			echo Debug::li($cache_file);
			*/
			$cache_location = $cache_folder.$cache_file;
			if(file_exists($cache_location)){
				$resultSerial = unserialize(file_get_contents($cache_location));
				//return $resultSerial;
				$obj = $resultSerial;
				$save_cache = FALSE;
			}
		}
		
		//o cache não está gravado
		if($obj == NULL){
			$obj = $this;
			//pedindo para consultar
			$this->consultValues();
		}
		$retornoDaPaginaHTML->arrayMaterial 	= $obj->arrayMaterial->result;
		$retornoDaPaginaHTML->arrayColor 		= $obj->arrayColor->result;
		$retornoDaPaginaHTML->arrayStyle 		= $obj->arrayStyle->result;
		$retornoDaPaginaHTML->arrayFormat 		= $obj->arrayFormat->result;
		$retornoDaPaginaHTML->arrayTheme 		= $obj->arrayTheme->result;
		$retornoDaPaginaHTML->arrayComposition 	= $obj->arrayComposition->result;
		$retornoDaPaginaHTML->arrayTechnique 	= $obj->arrayTechnique->result;
		$retornoDaPaginaHTML->arrayType 		= $obj->arrayType->result;
		
		$botaoPromocoes 		= new stdClass();
		$botaoPromocoes->name 	= "em promoção";
		//echo Debug::li($this->currentQuery);exit();
		if(!$this->currentQuery){
			$this->currentQuery = "SELECT count( DISTINCT `view_product_model_quadro_b`.id) as count FROM `view_product_model_quadro_b` WHERE 1 AND view_product_model_quadro_b.`active` = '1'";
		}
		$queryPromocao = $this->currentQuery." AND promocao = 1 ";
		//oic 2
		$dbi = new DbInterface();
		
		$rt = $dbi->query($queryPromocao);
		//echo Debug::li($this->currentQuery);
		if(isset($_GET["teste"])){
			Debug::print_r($rt);
		}
		$rt->fetchAll();
		//Debug::print_r($rt);exit();
		$botaoPromocoes->id 	= 1;
		$botaoPromocoes->total 	= $rt->result[0]->count;//total de produtos em promoção
		
		$retornoDaPaginaHTML->arrayPromocoes 	= array($botaoPromocoes);
		
		
		
		$retornoDaPaginaHTML->requestMaterial 		= $obj->requestMaterial;
		$retornoDaPaginaHTML->requestColor 			= $obj->requestColor;
		$retornoDaPaginaHTML->requestStyle 			= $obj->requestStyle;
		$retornoDaPaginaHTML->requestFormat 		= $obj->requestFormat;
		$retornoDaPaginaHTML->requestTheme 			= $obj->requestTheme;
		$retornoDaPaginaHTML->requestComposition 	= $obj->requestComposition;
		$retornoDaPaginaHTML->requestTechnique 		= $obj->requestTechnique;
		$retornoDaPaginaHTML->requestType 			= $obj->requestType;
		$retornoDaPaginaHTML->requestSearch 		= $obj->requestSearch;
		$retornoDaPaginaHTML->requestResumeSearch 	= $obj->requestResumeSearch;
		$retornoDaPaginaHTML->requestPromocao 		= $obj->requestPromocao;
		
		$retornoDaPaginaHTML->arrayRestFolder 	= $this->arrayRestFolder;
		$retornoDaPaginaHTML->arrayVariable 	= $this->arrayVariable;
		
		$retornoDaPaginaHTML->url_final = $obj->url_final;
		
		//salvando o objeto de resultado de html no retorno
		$returnResult->setHttpContentResult($retornoDaPaginaHTML);
		// -------------------------------------------------------------------[ BREAD CRUMB ]
		// Agora criando a array de bread crumb
		$retornoDaPaginaHTML->arrayBreadCrumb = $this->arrayBreadCrumb;
		// E também popula os títulos, que até então ele não tinha como pegar
		foreach($retornoDaPaginaHTML->arrayBreadCrumb as $BreadCrumbInfoVO){
			//pelo tipo de item e id ele ve o nome
			if(FALSE){
				//apenas para o auto completar do eclipse
				$BreadCrumbInfoVO = new BreadCrumbInfoVO();
			}
			$BreadCrumbInfoVO->tittle = $retornoDaPaginaHTML->getNameByTypeAndId($BreadCrumbInfoVO->reference, $BreadCrumbInfoVO->id);
		}
		if(Config::USE_QUERY_CACHE && $save_cache){
			//grava o cache
			DataHandler::createRecursiveFoldersIfNotExists($cache_folder);
			//criando obj simplificado para serializar
			$stdObj = new stdClass();
			$stdObj->arrayMaterial 		= $obj->arrayMaterial;
			$stdObj->arrayColor 		= $obj->arrayColor;
			$stdObj->arrayStyle 		= $obj->arrayStyle;
			$stdObj->arrayFormat 		= $obj->arrayFormat;
			$stdObj->arrayTheme 		= $obj->arrayTheme;
			$stdObj->arrayComposition 	= $obj->arrayComposition;
			$stdObj->arrayTechnique 	= $obj->arrayTechnique;
			$stdObj->arrayType			= $obj->arrayType;
			
			$stdObj->requestMaterial 		= $obj->requestMaterial;
			$stdObj->requestColor 			= $obj->requestColor;
			$stdObj->requestStyle 			= $obj->requestStyle;
			$stdObj->requestFormat 			= $obj->requestFormat;
			$stdObj->requestTheme 			= $obj->requestTheme;
			$stdObj->requestComposition 	= $obj->requestComposition;
			$stdObj->requestTechnique 		= $obj->requestTechnique;
			$stdObj->requestType 			= $obj->requestType;
			$stdObj->requestSearch 			= $obj->requestSearch;
			$stdObj->requestResumeSearch 	= $obj->requestResumeSearch;
			$stdObj->requestPromocao 		= $obj->requestPromocao;
			$stdObj->url_final 				= $obj->url_final;
			DataHandler::writeFile($cache_folder, $cache_file, serialize($stdObj));
		}
		return $returnResult;
	}
}