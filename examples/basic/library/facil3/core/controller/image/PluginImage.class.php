<?php
include_once "library/facil3/interface/FacilPlugin.class.php";
include_once "library/facil3/core/controller/image/ImageInfoPostHandler.class.php";
include_once "library/facil3/core/controller/image/FacilImage.php";
class PluginImage implements FacilPlugin{
	private $ImageInfoPostHandler;
	/**
	 * @param $InfoPost objeto com as informações enviadas, arrays, informações e etc
	 * @return void
	 */
	public function __construct($InfoPost = NULL){
		$this->ImageInfoPostHandler = new ImageInfoPostHandler($InfoPost->info, $InfoPost->array_files);
	}
	/**
	 * @param $VinculedBaseVO qualquer classe que tenha getId e a propriedade table
	 * @return ReturnResultVO de preferencia
	 */
	public function commit($VinculedBaseVO = NULL){
		//considerando que as informações principais já foram passadas na construct
		if($VinculedBaseVO){
			//se passar o VinculedBaseVO ele atribui a table e table_id para todas as entradas
			$this->ImageInfoPostHandler->setLinkedTableAndTableId($VinculedBaseVO->getTable(), $VinculedBaseVO->getId());
		}
		//agora sim vai usar o modulo de imagem
		$FacilImage = new FacilImage();
		//configurar o geral do FacilImage
		//$FacilImage->defaultFolderForNewImages = "";
		$ReturnResultVO = new ReturnResultVO();
		$ReturnResultVO->result = TRUE;
		foreach($this->ImageInfoPostHandler->getArrayInfoPost() as $InfoPost){
			$FacilImage->resetInfoPost($InfoPost);
			$ReturnResultVOtemp = $FacilImage->insert();
			if(!$ReturnResultVOtemp->result){
				$ReturnResultVO->result = $ReturnResultVOtemp->result;
			}
			
		}
		// ve se foi enviado a array de delete e passa para o metodo delete
		foreach($this->ImageInfoPostHandler->getArrayToDelete() as $delete_id){
			//nesse caso não trata o retorno, simplesmente manda fazer
			$this->delete($delete_id);
		}
		
	}
	/**
	 * Cada plugin deve saber se ao deletar precisa apagar arquivos, desvincular, excluir ou não do banco
	 * @param $item_id int da entrada do item a ser deletado no banco.
	 * @return ReturnResultVO
	 */
	public function delete($item_id){
		//aqui precisa realmente deletar a imagem, ou desvincula-la, o que é melhor
		include_once "library/facil3/core/dao/LinkDAO.class.php";
		$LinkDAO = LinkDAO::getInstance();
		if(FALSE){
			$LinkDAO = new LinkDAO();
		}
		//desvincula a foto ao table e table_id enviado
		$ReturnResultVinculoVO = $LinkDAO->deleteAllByTableAndTableId("image", $item_id);
		return $ReturnResultVinculoVO;
	}
}