<?php
	/**
	 * @author		: Mauricio Amorim
	 * @date		: 10/11/2010
	 * @version		: 1.0
	 * @desc		: tabela view_product_model_quadro = tabla product_model + inner join de tabela product_model_democrart_quadrado  
	 * 
	 *tabelas com padrao
	 					id,
						active, 
						category_id,
						name,
						slug,
						order

	 */

	include_once "library/".Config::FOLDER_APPLICATION."/modules/category/vo/CategoryEncVO.class.php";
	include_once "library/facil3/core/modules/category/dao/CategoryDAO.class.php";
	
	include_once "library/facil3/core/DbInterface.class.php";
	include_once "library/facil3/interface/dao/PatternDAO.class.php";
	
	class CategoryEncDAO extends CategoryDAO implements PatternDAO{
		protected static $my_instance = NULL;
		public static function getInstance(){
			if(!self::$my_instance){
				self::$my_instance = new CategoryEncDAO();
			}
			return self::$my_instance;
		}
		public function getVO(){
			return new CategoryEncVO();
		}		

		public function __construct(){
			parent::__construct();
		}

}