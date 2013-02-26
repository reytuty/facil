<?php 
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @description	: Essa classe representa o padrão de retorno para as DAOs e DbIterface
	 */

class ReturnDataVO{
	public $success		   = FALSE;
	public $result 		   = NULL;
	public $code_return	   = NULL;
	public $count_total    = NULL;
    public $query          = NULL;
    public $uniqueResult   = FALSE;
	
	const TYPE_FETCH_OBJECT 	= "o";
	const TYPE_FETCH_ARRAY 		= "a";
	
	private $result_fetch_array;
	private $result_fetch_object;
	private $result_fetch_vo;
	/**
	 * só existe se o return_id for passada na query e se for possível retornar o id
	 * @var int
	 */
	private $return_id;
	public function getReturnId(){
		return $this->return_id;
	}
	public function setReturnId($id){
		if(!$this->return_id){
			$this->return_id = $id;
		} else {
			//de novo nao
		}
	}
	/**
	 * @param bool $success
	 * @param return data $result
	 * @param int $code_return
     * @param string $query 
     * @param bool $uniqueResult 
	 */
	public function __construct($success = FALSE, $result = NULL, $code_return = NULL, $query = NULL, $uniqueResult = NULL){
		$this->success 		= $success;
		$this->result 		= $result;
		$this->code_return	= $code_return;
        $this->query        = $query;
        $this->uniqueResult = $uniqueResult;
        //$this->autoErrorReport();
	}
    
    public function autoErrorReport(){
        if(defined('DEV') && DEV == true && $this->success == FALSE && $this->query !== NULL){
            if(!class_exists('Debug')){
                include_once("library/facil3/utils/Debug.class.php");
            }
            Debug::print_r($this);
        }
    }
    /**
     * tranforma o Result em 
     * @return void
     */
    public function transformAllVoInStdClass(){
    		if($this->success && count($this->result_fetch_vo) > 0){
    			$temp_array = array();
				foreach($this->result_fetch_vo as $tempVO){
					$temp_array[] = $tempVO->toStdClass();
				}
				$this->result = $temp_array;
			}
    }
	public function fetchAll($type = ReturnDataVO::TYPE_FETCH_OBJECT){
		//n�o utilizar esse metodo 2 vezes
		//verificar se result não é null
		//da um fetch no pr�prio resultado
		switch($type){
			case ReturnDataVO::TYPE_FETCH_ARRAY:
				//varrendo o result e redefinindo-o
				if(!$this->result_fetch_array){
					$this->result_fetch_array 	= $this->fetchArray($this->result);
				}
				$this->result 				= $this->result_fetch_array; 
				break;
			case ReturnDataVO::TYPE_FETCH_OBJECT:
			default:
				//vai retornar como objeto
				if(!$this->result_fetch_object){
					$this->result_fetch_object 	= $this->fetchObject($this->result);
				}
				$this->result                   = $this->result_fetch_object; 
				break;
		}
        
        if($this->uniqueResult == TRUE){
            if(isset($this->result[0])){
                $this->result = $this->result[0];
            }else{
                $this->success = FALSE;
                $this->result = FALSE;
            }
        }
	}
	private function fetchObject($mysql_result){
		$temp_result = array();
        while($r = mysql_fetch_object($mysql_result)){
			$temp_result[] = $r;
		}
		return $temp_result;
	}
	private function fetchArray($mysql_result){
		$temp_result = array();
		if($mysql_result){
			while($r = mysql_fetch_array($mysql_result)){
				$temp_result[] = $r;
			}
		}
		return $temp_result;
	}
	public function fetchAllVO($DAO){
		if(!$this->result_fetch_vo){
			$temp_result = array();
			$this->result = $this->fetchObject($this->result);
			for($i = 0; $i < count($this->result); $i++){
				$tempVO = $DAO->getVO();
				$tempVO->setFetchObject($this->result[$i]);
				$temp_result[] = $tempVO;
			}
			$this->result_fetch_vo = $temp_result;
		}
		$this->result = $this->result_fetch_vo;
	}
	public function getCode(){
		return $this->code_return;
	}
}