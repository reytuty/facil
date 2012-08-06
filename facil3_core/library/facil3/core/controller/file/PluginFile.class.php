<?php
include_once "library/facil3/interface/FacilPlugin.class.php";
include_once "library/facil3/core/controller/file/FileInfoPostHandler.class.php";
include_once "library/facil3/core/controller/file/FacilFile.php";
class PluginFile implements FacilPlugin{
	private $FileInfoPostHandler;
	/**
	 * @param $InfoPost objeto com as informações enviadas, arrays, informações e etc
	 * @return void
	 */
	public function __construct($InfoPost = NULL){
		$this->FileInfoPostHandler = new FileInfoPostHandler($InfoPost->info, $InfoPost->array_files);
	}
	/**
	 * @param $VinculedBaseVO qualquer classe que tenha getId e a propriedade table
	 * @return ReturnDataVO de preferencia
	 */
	public function commit($VinculedBaseVO = NULL){
		//considerando que as informações principais já foram passadas na construct
		if($VinculedBaseVO){
			//se passar o VinculedBaseVO ele atribui a table e table_id para todas as entradas
			$this->FileInfoPostHandler->setLinkedTableAndTableId($VinculedBaseVO->getTable(), $VinculedBaseVO->getId());
		}
		//agora sim vai usar o modulo de filem
		$FacilFile = new FacilFile();
		//configurar o geral do FacilFile
		//$FacilFile->defaultFolderForNewFiles = "";
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->result = TRUE;
		foreach($this->FileInfoPostHandler->getArrayInfoPost() as $InfoPost){
			$FacilFile->resetInfoPost($InfoPost);
			$ReturnResultVOtemp = $FacilFile->insert();
			if(!$ReturnResultVOtemp->result){
				$ReturnResultVO->result = $ReturnResultVOtemp->result;
			}
			
		}
		// ve se foi enviado a array de delete e passa para o metodo delete
		foreach($this->FileInfoPostHandler->getArrayToDelete() as $delete_id){
			//nesse caso não trata o retorno, simplesmente manda fazer
			$this->delete($delete_id);
		}
		
	}
	/**
	 * Cada plugin deve saber se ao deletar precisa apagar arquivos, desvincular, excluir ou não do banco
	 * @param $item_id int da entrada do item a ser deletado no banco.
	 * @return ReturnDataVO
	 */
	public function delete($item_id){
		//aqui precisa realmente deletar a filem, ou desvincula-la, o que é melhor
		include_once "library/facil3/core/dao/LinkDAO.class.php";
		$LinkDAO = LinkDAO::getInstance();
		if(FALSE){
			$LinkDAO = new LinkDAO();
		}
		//desvincula a foto ao table e table_id enviado
		$ReturnResultVinculoVO = $LinkDAO->deleteAllByTableAndTableId("file", $item_id);
		return $ReturnResultVinculoVO;
	}
}