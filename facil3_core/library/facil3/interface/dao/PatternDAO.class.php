<?php 
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 */

Interface PatternDAO{
	public static function getInstance();
	/**
	 * @param $VO
	 * @return ReturnDataVO
	 */
	public function insertVO($VO);
	/**
	 * @param $VO
	 * @return ReturnDataVO
	 */
	public function updateVO($VO);
	//public function insert();
	/**
	 * @param int $id
	 * @return ReturnDataVO
	 */
	public function delete($id);
	/**
	 * @param int $id
	 * @return ReturnDataVO
	 */
	public function active($id);
	
	/**
	 * @param $id
	 * @return RuturnDataVO;
	 */
	public function deactive($id);
	//public function update(*);
	public function getVO();
} 