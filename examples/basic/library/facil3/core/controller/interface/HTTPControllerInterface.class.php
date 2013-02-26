<?php

/**
 * @author 	Renato Miawaki
 * @desc	Interface de controlers de HTTP
 */
Interface HTTPControllerInterface{
	function __construct($arrayRestFolder = NULL);
	function init();
}