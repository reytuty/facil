<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once "library/facil3/utils/Debug.class.php";

include_once "library/facil3/core/http/HttpRequestController.php";
$HttpRequestController = new HttpRequestController();

echo $HttpRequestController->getResult();
