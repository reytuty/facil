<?php
/**
 * @author		: Renato Miawaki
 * @data		: 1/12/2010
 * @version		: 1.0
 * @description	: contem o básico de toda DAO, só o que é possível implementar de modo automático
 */
 	include_once "facil3/core/DbInterface.class.php";
    
    class BaseDAO extends DbInterface{
    	/**
    	 * @param  int $id
         * @return ReturnDataVO 
         */
        public function active($id){
    		if(isset($this->TABLE_NAME)){
	            $query = "UPDATE ".$this->TABLE_NAME." SET `active` = 1 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
	            $ReturnDataVO = parent::query($query);
	            return $ReturnDataVO;
    		} else {
    			throw new Exception("BaseDAO . active : precisa de protected \$TABLE_NAME definida");
    		}
        }
        /**
    	 * @param  int $id
         * @return ReturnDataVO 
         */
        public function deactive($id){
            $query = "UPDATE ".$this->TABLE_NAME." SET `active` = 0 WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
            $ReturnDataVO = parent::query($query);
            return $ReturnDataVO;
        }
        /**
         * deleta mesmo
    	 * @param  int $id
         * @return ReturnDataVO 
         */
        public function delete($id){
            $query = "DELETE FROM ".$this->TABLE_NAME." WHERE id = '".DataHandler::forceInt($id)."' LIMIT 1 ";
            $ReturnDataVO = parent::query($query);
            return $ReturnDataVO;
        }
    	/**
	     * @param $id number
	     * @return class ReturnDataVO 
	     */
		public function selectById($id){
			$ReturnDataVO = $this->select(DbInterface::RETURN_STD_OBJECT, $id);
			return $ReturnDataVO;
		}
		/**
		 * executa automáticamente o fetchXPTO conforme typeOfReturn. transforma o ReturnDataVO enviado
		 * @param string $typeOfReturn
		 * @param ReturnDataVO $ReturnDataVO
		 * @return void
		 */
		protected function transformReturnDataByTypeReturn($typeOfReturn, ReturnDataVO &$ReturnDataVO){
			switch($typeOfReturn){
					case DbInterface::RETURN_ARRAY:
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_ARRAY);
						break;
					case DbInterface::RETURN_VO:
						$ReturnDataVO->fetchAllVO($this);
						break;
					case DbInterface::RETURN_STD_OBJECT:
					default:
						//retornar tudo em objeto
						$ReturnDataVO->fetchAll(ReturnDataVO::TYPE_FETCH_OBJECT);
						break;
				}
		}
    }