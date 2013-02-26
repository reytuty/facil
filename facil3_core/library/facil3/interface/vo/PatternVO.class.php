<?php
	/**
	 * @author		: Mauricio Amorim
	 * @data		: 05/11/2010
	 * @version		: 1.0
	 * @description	: 	Interface para VOs
	 * 					Pra que serve Interface?
						http://kaizenweb.com.br/wordpress/?p=77
	 */
Interface PatternVO{
    //var $DAO;
    /**
	 * Esse metodo inicia o objeto DAO
	 * 
	 * @return void
	 */
    public function startDAO();
    /**
	 * Setar o id do objeto
	 * @param (int) $id/
	 * @param (boolean) $autoBusca [opcional, default = false]
	 * @return boolean
	 */
    public function setId($id, $autoSearch = FALSE);
    /**
	 * faz a busca automatica baseada no id do objeto
	 * @return boolean
	 */
    public function autoSearch();
    /**
	 * Envie um objeto tratado do banco para popular atuomaticamente a VO
	 * @param (object) $fetch_obj
	 * @return void
	 */
    public function setFetchObject($fetch_obj);
    /**
	 * Pode enviar diretamente o que vem do POST ou GET aqui para popular a VO
	 * @param (array) $fetch_array
	 * @return void
	 */
    public function setFetchArray($fetch_array);
    /**
	 * Este metodo cadastra ou atualiza uma VO, dependendo dos dados preenchidos
	 * @return array
	 */
    public function commit($validate = FALSE);
    /**
	 * Exclue uma entrada no banco baseado no id deste objeto
	 * @return array
	 */
    public function delete();
    /**
	 * Retorna array de string
	 * @param $resultado
	 * @param $arrayResultado
	 * @return void
	 */
    public function resultHandler($result, &$arrayResult);
    /**
	 * Retorna o id
	 * @return int
	 */
    public function getId();
    /**
	 * Desativa uma entrada no banco baseada no id desse objeto
	 * @return array
	 */
    public function deactive();
    /**
	 * Ativa uma entrada no banco baseada no id desse objeto
	 * @return array
	 */
    public function active();
    /**
	 * Verifica se os dados preenchidos nessa VO atendem as necessidades para cadastro
	 * @return array
	 */
    public function validate();
}
?>