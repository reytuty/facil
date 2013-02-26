<?php 
/**
 * @author		: Mauricio de Amorim
 * @date		: 07/11/2010
 * @version		: 1.0
 * @description	: Objeto Padrão onde são iniciados os campos utilizados para a View Product Model Details
 * @Obs 		:é extenção de uma classe com meta tags pré-definidas.
 **/
include_once(Config::FOLDER_APPLICATION."http/DemocrartPage.class.php");
class HttpMenu extends DemocrartPage{
	//array de Ojetos std
	public $arrayMaterial;
	public $arrayColor;
	public $arrayStyle;
	public $arrayFormat;
	public $arrayTheme;
	public $arrayComposition;
	public $arrayTechnique;
	public $arrayType;
	public $arrayPromocoes;
	//id dos respectivos tipos
	public $requestMaterial;
	public $requestColor;
	public $requestStyle;
	public $requestFormat;
	public $requestTheme;
	public $requestComposition;
	public $requestTechnique;
	public $requestType;
	public $requestResumeSearch;
	
	public $url_final;
	/**
	 * @var array com o bread crumb baseado nas variaveis da navegação
	 */
	public $arrayBreadCrumb;
	public function __construct(){
		parent::__construct();
		$this->http_header->setTitle(Translation::text("Democrart . artistas cadastrados"));
		$this->http_header->setDescription(Translation::text("lista de artistas cadastrados no democrart"));
		$this->http_header->setKeywords(Translation::text("lista de artistas cadastrados no democrart"));
	}
	/**
	 * @param $type string dessa classe: requestMaterial, requestColor, requestStyle, requestFormat, requestTheme, requestComposition, requestTechnique, requestType
	 * @param $id do item na array
	 * @return string do nome do item, se não encontrar retorna vazio
	 */
	public function getNameByTypeAndId($type, $id){
		if($type == "search"){
			//se for busca, retorna o que buscou pois é isso mesmo
			return urldecode($this->requestResumeSearch);
		}
		if($type == "promocoes"){
			return Translation::text("Promoções");
		}
		//iniciando a array//pegando a array pelo tipo
		$array_type = $this->getArrayByType($type);
		foreach($array_type as $item){
		  	if($item->id == $id){
		  		return $item->name;
		  	}
		}
		return "";
	}
	/**
	 * @param $type string dessa classe: requestMaterial, requestColor, requestStyle, requestFormat, requestTheme, requestComposition, requestTechnique, requestType
	 * @param $name do item na array
	 * @return string do id do item, se não encontrar retorna vazio
	 */
	public function getIdByTypeAndName($type, $name){
		$array_type = $this->getArrayByType($type);
		foreach($array_type as $item){
		  	if($item->name == $name){
		  		return $item->id;
		  	}
		}
		return "";
	}
	/**
	 * @param $type string da array
	 * @return array
	 */
	public function getArrayByType($type){
		switch($type){
			case "material":
				return $this->arrayMaterial;
				break;
			case "color":
				return $this->arrayColor;
				break;
			case "style":
				return $this->arrayStyle;
				break;
			case "format":
				return $this->arrayFormat;
				break;
			case "theme":
				return $this->arrayTheme;
				break;
			case "composition":
				return $this->arrayComposition;
				break;
			case "technique":
				return $this->arrayTechnique;
				break;
			case "type":
				return $this->arrayType;
				break;
			case "promocoes":
				return $this->arrayPromocoes;
				break;
		}//end switch
		return array();//array vazia se não for nenhum tipo
	}
}