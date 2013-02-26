<?php
/*
	 * @author		: Pinga da Silva
	 * @data		: 09/07/2010
	 * @version		: 1.0
	 * @description	: 	Classe para controle de permissões vindas ou não do banco
	 					Guarda apenas os tipos de permissão e boolean para o valor
							
	 */
if(!isset( $WEBSERVICEACCESS ) || !$WEBSERVICEACCESS){
	echo Translation::text("LibraryLanguage::ERROR_ACCESS_WEBSERVICE");
	exit();
}
class PermissionVO{
	public $read 	= FALSE;
	public $insert 	= FALSE;
	public $delete 	= FALSE;
	public $update 	= FALSE;
	public $special	= FALSE;
	public $array_script = array();
	public function __construct($read = FALSE, $insert = FALSE, $delete = FALSE, $update = FALSE, $special = FALSE){
		$this->read 	= $read;
		$this->insert 	= $insert;
		$this->delete 	= $delete;
		$this->update 	= $update;
		$this->special	= $special;
	}
	public function addScriptPublicPermission($script_name, $HAVE_PERMISSION = TRUE){
		$this->array_script[] = new PermissionItemVO($script_name, $HAVE_PERMISSION);
	}
	public function removeScriptPublicPermission($script_name, $HAVE_PERMISSION = FALSE){
		foreach($this->array_script as $PermissionItemVO){
			if($PermissionItemVO->script_name == $script_name){
				$PermissionItemVO->permission = $HAVE_PERMISSION;
			}
		}
	}
	public function getScriptPermission($script_name){
		foreach($this->array_script as $PermissionItemVO){
			if($PermissionItemVO->script_name == $script_name){
				return ($PermissionItemVO->permission == TRUE);
			}
		}
		return FALSE;
	}
}
class PermissionItemVO{
	public $script_name;
	public $permission;
	public function __construct($script_name = NULL, $permission = TRUE){
		$this->script_name 		= $script_name;
		$this->permission	= ($permission == TRUE);
	}
}