<?php
include_once "library/facil3/core/controller/interface/HTTPControllerInterface.class.php" ;
/**
 * @author Renato Miawaki
 *
 */
interface AbstractController extends HTTPControllerInterface{
	function resetInfoPost($InfoPost);
	/**
	 * para buscar 1 item
	 * @return unknown_type
	 */
	function get();
	/**
	 * para listar
	 * @return unknown_type
	 */
	function select();
	/**
	 * para inserir 1 item
	 * @return unknown_type
	 */
	function insert();
}