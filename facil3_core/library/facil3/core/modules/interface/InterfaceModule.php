<?php
/**
 * @author 	Renato Miawaki
 * @desc	Módulos precisam ter uma classe que atenda a essa interface.
 * 			Os módulos são registrados e iniciados automáticamente na config do sistema
 * 			Os níveis de permissão
 */
Interface InterfaceModule {
	function init();
	function getInstance();
	function getVO();
	function getDAO();
}