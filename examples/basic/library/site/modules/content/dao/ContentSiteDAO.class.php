<?php
include_once "library/facil3/core/modules/content/dao/ContentDAO.class.php";
include_once Config::FOLDER_APPLICATION."modules/content/vo/ContentSiteVO.class.php";
/**
 * @author 		: Renato Miawaki
 *	@desc 		: para facilitar  a vida nesse projeto extendi o módulo para que a VO tenha 2 novos metodos
 */
class ContentSiteDAO extends ContentDAO{
	protected static $my_instance = NULL;
	public static function getInstance(){
		if(!self::$my_instance){
			self::$my_instance = new ContentSiteDAO();
		}
		return self::$my_instance;
	}
	public function getVO(){
		return new ContentSiteVO();
	}
}