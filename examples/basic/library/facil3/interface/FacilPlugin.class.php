<?php

/**	
 * @author 		: Renato Miawaki
 * @desc		: Interface de plugins para serem usados em controllers
 *
 */
interface FacilPlugin{
	/**
	 * @param $InfoPost objeto com as informações enviadas, arrays, informações e etc
	 * @return void
	 */
	function __construct($InfoPost = NULL);
	/**
	 * @param $VinculedBaseVO qualquer classe que tenha getId e a propriedade table
	 * @return ReturnDataVO de preferencia
	 */
	function commit($VinculedBaseVO = NULL);
	/**
	 * Cada plugin deve saber se ao deletar precisa apagar arquivos, desvincular, excluir ou não do banco
	 * @param $item_id int da entrada do item a ser deletado no banco.
	 * @return ReturnDataVO
	 */
	function delete($item_id);
}