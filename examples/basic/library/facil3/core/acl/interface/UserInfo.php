<?php
Interface UserInfo{
	public static function getTypeId();
	public static function getId();
	public static function getName();
	public static function getToken();
	public static function getActiveTime();
	public static function getActive();
}