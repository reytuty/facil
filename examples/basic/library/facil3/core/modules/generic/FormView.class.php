<?php

	/**
	 * @author		: Alan Lucian M. Tormente
	 * @date		: 21/01/2011
	 * @version		: 1.0
	 * @description	: data manipulator for all kind of FormView class
	 * 
	 * library/facil3/core/modules/generic/FormView.class.php
	 * 
	 */
	 
class FormView {
	protected $form_label;
	protected $show_image_url;
	
	var $ignoreList = array();
	
	function __construct(){
		$this->ignoreMethod(array('setMassiveAttr', 'setFormLabel', 'setLocale', 'setShowImageUrl'));
	}
	
	/*
	 * add a method to ignore list on getFieldData
	 */
	public function ignoreMethod($method){
		if(is_array($method)){
			foreach($method as $m)
				array_push($this->ignoreList, $m);
		}else{
			array_push($this->ignoreList, $method);	
		}		
		
	}
	
	public function setMassiveAttr($attr, $value, $arr_properties){
		if(!is_array($arr_properties)){
			throw new ErrorException("setMassiveAttr exige um array no argumento $arr_properties");
		}
		$arr_arg = array($attr=>$value);	
		foreach($arr_properties as $prop){
			$method = "set$prop";
			$this->$method($arr_arg);
		}
	}
	
	/**
	 * @desc name a ser exibido no label do form
	 * @param $value
	 * @return void
	 */
	public function setFormLabel($value){
		$this->form_label = $value;
	}
	
	/**
	 * @desc name a ser exibido no label do form
	 * @return string
	 */
	public function getFormLabel(){
		return $this->form_label;
	}
	
	/**
	 * @desc se true exibe a url da umagem logo abaixo da imagem
	 * @param $value (boolean)
	 * @return void
	 */
	public function setShowImageUrl($value){
		$this->show_image_url = DataHandler::forceInt($value);
	}
	
	/**
	 * @desc se true exibe a url da umagem logo abaixo da imagem
	 * @param $value (boolean)
	 * @return string
	 */
	public function getShowImageUrl(){
		return $this->show_image_url;
	}
	/**
	 * @return stdClass com propriedades necessárias para o padrão determinado de form view
	 */
	public function getFieldData(){
		$fieldData = new stdClass();
		$arrayMetodos = get_class_methods($this);
		//varre todos os metodos desssa classe
		foreach($arrayMetodos as $metodo){
			//se não estiver na ignore liste E começar com "set"
			if( !in_array($metodo, $this->ignoreList) && preg_match("/^set.+/", $metodo)){
				 //transforma o nome da array em nome de propriedade, ex: setExemploFelix vira exemplo_feliz
				$propertie = preg_replace("/^set_(.*)/", "$1", DataHandler::ecmaToUnderline($metodo));
				//se a propriedade for nula
				if( $this->$propertie == NULL){
					//da o set pelado, chamando o metodo
					$this->$metodo();
				}
				//mesmo SEM PASSAR PELO SET, vai pegar a propriedade
				//guardando numa variavel o objeto da propriedade
				$tempValor = (object) $this->$propertie;
				//setando como propriedade do fieldData
				$fieldData->$propertie =   $tempValor;
			}
		}
		
		return $fieldData;
		
	}
	
}
	