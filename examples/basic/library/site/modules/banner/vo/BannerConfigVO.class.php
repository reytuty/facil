<?php
include_once "library/facil3/core/modules/category/vo/CategoryVO.class.php";

class BannerConfigVO extends CategoryVO{
	private $transitionType;
	public function getTransitionType(){
		if(!$this->transitionType){
			$this->transitionType = 2;//o padrao é 2
			$ParameterTransitionType = $this->getParamethersByValues("transitionType");
			if(count($ParameterTransitionType) > 0){
				$this->transitionType = $ParameterTransitionType[0]->getValue();
			}
		}
		return $this->transitionType;
	}
	public function setTransitionType($value){
		$this->transitionType = $value;
		if($this->transitionType < 1){
			$this->transitionType = 1;//minimo 1
		}
		if($this->transitionType > 5){
			$this->transitionType = 5;//máximo 5
		}
		$ParameterTransitionType = $this->getParamethersByValues("transitionType");
//		echo ":transitionType";
//		Debug::print_r($ParameterTransitionType);
		if(count($ParameterTransitionType) > 0){
			$temp = $ParameterTransitionType[0];
			$this->addParamether("int", "transitionType", $this->transitionType, NULL, $temp->getId());
		} else {
			$this->addParamether("int", "transitionType", $this->transitionType);
		}
	}
	
	private $transitionDelayTimeFixed;
	public function getTransitionDelayTimeFixed(){
		if(!$this->transitionDelayTimeFixed){
			$this->transitionDelayTimeFixed = 2;//o padrao é 2
			$ParameterTransitionType = $this->getParamethersByValues("transitionDelayTimeFixed");
			if(count($ParameterTransitionType) > 0){
				$this->transitionDelayTimeFixed = $ParameterTransitionType[0]->getValue();
			}
		}
		return $this->transitionDelayTimeFixed;
	}
	public function setTransitionDelayTimeFixed($value){
		$this->transitionDelayTimeFixed = $value;
		if($this->transitionDelayTimeFixed < 1){
			$this->transitionDelayTimeFixed = 1;//minimo 1
		}
		if($this->transitionDelayTimeFixed > 10){
			$this->transitionDelayTimeFixed = 10;//máximo 10
		}
		$ParameterTransitionType = $this->getParamethersByValues("transitionDelayTimeFixed");
//		echo ":transitionDelayTimeFixed";
//		Debug::print_r($ParameterTransitionType);
		if(count($ParameterTransitionType) > 0){
			$temp = $ParameterTransitionType[0];
			if(FALSE){
				$temp = new ParametherVO();
			}
			$this->addParamether("int", "transitionDelayTimeFixed", $this->transitionDelayTimeFixed, NULL, $temp->getId());
		} else {
			$this->addParamether("int", "transitionDelayTimeFixed", $this->transitionDelayTimeFixed);
		}
	}
	
//	textSize="24"
//    textColor=""
//    textAreaWidth=""
//    textLineSpacing="0" 
//    textLetterSpacing="-0.5"    
//    textMarginLeft="12"
//    textMarginBottom="5"
//    
//    transitionType="'.$transitionType.'"
//    transitionDelayTimeFixed="4" 
//    transitionDelayTimePerWord=".5"
//    transitionSpeed="2"
//    transitionBlur="yes"
//    transitionRandomizeOrder="no"   
//    
//    showTimerClock="no"
//    showBackButton="yes"
//    showNumberButtons="yes"
//    showNumberButtonsAlways="no"
//    showNumberButtonsHorizontal="yes"
//    showNumberButtonsAscending="yes" 
//    autoPlay="yes"
}