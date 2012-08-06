<?php

/**
 * @author Renato Miawaki
 * @desc 	Cada item de um bread crumb é uma instancia dessa.
 */
class BreadCrumbInfoVO{
	public $tittle;
	public $reference;
	public $id;
	public $link;
	public function __construct($tittle, $reference = NULL, $id = NULL, $link = NULL){
		$this->tittle		= strip_tags($tittle);
		$this->reference	= $reference;
		$this->id			= $id;
		$this->link			= $link;
	}
}