<?php
/****************************************************
CallerService.php

This file uses the constants.php to get parameters needed 
to make an API call and calls the server.if you want use your
own credentials, you have to change the constants.php

Called by TransactionDetails.php, ReviewOrder.php, 
DoDirectPaymentReceipt.php and DoExpressCheckoutPayment.php.

****************************************************/
include_once 'library/e_commerce/modules/paypal/vo/PayPalConfigVO.class.php';

class PayPalCallerService{
	public static function nvpHeader(){
		$nvpHeaderStr = "";
		$AuthMode = "AUTH_MODE";
		if(defined('AUTH_MODE')) {
			//$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
			//$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
			//$AuthMode = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
			$AuthMode = "AUTH_MODE"; 
		} else {
			if((!empty(PayPalConfigVO::$API_USERNAME)) && (!empty(PayPalConfigVO::$API_PASSWORD)) && (!empty(PayPalConfigVO::$API_SIGNATURE)) && (!empty(PayPalConfigVO::$SUBJECT))) {
				$AuthMode = "THIRDPARTY";
			} else if((!empty(PayPalConfigVO::$API_USERNAME)) && (!empty(PayPalConfigVO::$API_PASSWORD)) && (!empty(PayPalConfigVO::$API_SIGNATURE))) {
				$AuthMode = "3TOKEN";
			} elseif (!empty(PayPalConfigVO::$AUTH_TOKEN) && !empty(PayPalConfigVO::$AUTH_SIGNATURE) && !empty(PayPalConfigVO::$AUTH_TIMESTAMP)) {
				$AuthMode = "PERMISSION";
			} elseif(!empty(PayPalConfigVO::$SUBJECT)) {
				$AuthMode = "FIRSTPARTY";
			}
		}
		switch($AuthMode) {
			
			case "3TOKEN" : 
					$nvpHeaderStr = "&PWD=".urlencode(PayPalConfigVO::$API_PASSWORD)."&USER=".urlencode(PayPalConfigVO::$API_USERNAME)."&SIGNATURE=".urlencode(PayPalConfigVO::$API_SIGNATURE);
					break;
			case "FIRSTPARTY" :
					$nvpHeaderStr = "&SUBJECT=".urlencode(PayPalConfigVO::$SUBJECT);
					break;
			case "THIRDPARTY" :
					$nvpHeaderStr = "&PWD=".urlencode(PayPalConfigVO::$API_PASSWORD)."&USER=".urlencode(PayPalConfigVO::$API_USERNAME)."&SIGNATURE=".urlencode(PayPalConfigVO::$API_SIGNATURE)."&SUBJECT=".urlencode(PayPalConfigVO::$SUBJECT);
					break;		
			case "PERMISSION" :
					$nvpHeaderStr = formAutorization(PayPalConfigVO::$AUTH_TOKEN,PayPalConfigVO::$AUTH_SIGNATURE,PayPalConfigVO::$AUTH_TIMESTAMP);
					break;
		}
		return $nvpHeaderStr;
	}
	
	/**
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	*/
	
	
	public static function hash_call($methodName,$nvpStr){
		//declaring of global variables
		// form header string
		$nvpheader = self::nvpHeader();
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,PayPalConfigVO::$API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		//in case of permission APIs send headers as HTTPheders
		if(!empty(PayPalConfigVO::$AUTH_TOKEN) && !empty(PayPalConfigVO::$AUTH_SIGNATURE) && !empty(PayPalConfigVO::$AUTH_TIMESTAMP))
		 {
			$headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
	  
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
	    curl_setopt($ch, CURLOPT_HEADER, false);
		}
		else 
		{
			$nvpStr=$nvpheader.$nvpStr;
		}
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if(USE_PROXY)
		curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 
	
		//check if version is included in $nvpStr else include the version.
		if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
			$nvpStr = "&VERSION=" . urlencode(PayPalConfigVO::$VERSION) . $nvpStr;	
		}
		
		$nvpreq="METHOD=".urlencode($methodName).$nvpStr;
		
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
	
		//getting response from server
		$response = curl_exec($ch);
	
		//convrting NVPResponse to an Associative Array
		$nvpResArray=deformatNVP($response);
		$nvpReqArray=deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;
	
		if (curl_errno($ch)) {
			// moving to display page to display curl errors
			  $_SESSION['curl_error_no']=curl_errno($ch) ;
			  $_SESSION['curl_error_msg']=curl_error($ch);
			  $location = "APIError.php";
			  header("Location: $location");
		 } else {
			 //closing the curl
				curl_close($ch);
		  }
	
		return $nvpResArray;
	}
	
	/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	  */
	
	function deformatNVP($nvpstr)
	{
	
		$intial=0;
	 	$nvpArray = array();
	
	
		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
	
			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}
	function formAutorization($auth_token,$auth_signature,$auth_timestamp)
	{
		$authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
		return $authString;
	}
}

