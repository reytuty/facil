<?php


class DefautAdmin{
	public function __construct(){
		// echo "ta aqui.".UserClient::getTypeId();exit();
		if(!UserClient::isAlive() || (UserClient::getTypeId() != Config::ADMIN_USER_TYPE_ID && UserClient::getTypeId() != Config::DEVELOPER_USER_TYPE_ID)){
			$urlTo = Navigation::getURI(Config::$URL_ROOT_APPLICATION);
			Navigation::redirect("backend/login/to/".implode("/", $urlTo));
		}
	}
}

?>