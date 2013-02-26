<?php
include_once "library/facil3/core/controller/interface/HTTPControllerInterface.class.php" ;
/**
 * @author Renato Miawaki
 *
 */
class GenericAdminController implements HTTPControllerInterface{
	protected $arrayVariable;
	protected $arrayRestFolder;
	public function __construct($arrayRestFolder = NULL){
		//verifica se o sujeito está logado e se é admin
		if(!UserClient::isAlive()){
			Navigation::redirect("admin/login");
		} else if((UserClient::getTypeId() != Config::ADMIN_USER_TYPE_ID && UserClient::getTypeId() != Config::DEVELOPER_USER_TYPE_ID)){
			//o sujeito ou não está logado ou ele não é admin
			Navigation::redirect("admin/login");
		}
		if($arrayRestFolder != NULL){
			$this->arrayVariable   = Navigation::getVariableArraySlug($arrayRestFolder);
            $this->arrayRestFolder = $arrayRestFolder;
        }
	}
	public function init(){
		//nada aqui
	}
}