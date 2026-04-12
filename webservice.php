<?php
session_start();
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
	
	require_once("config.inc.php");
	require_once("include/HTTP_Session/Session.php");
	require_once 'include/Webservices/Utils.php';
	require_once("include/Webservices/State.php");
	require_once("include/Webservices/OperationManager.php");
	require_once("include/Webservices/SessionManager.php");
	require_once("include/Zend/Json.php");
	require_once('include/logging.php');
	require_once "include/language/$default_language.lang.php";
	
	/*require_once('modules/Users/Users.php');
	$current_user = new Users();
	$current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);*/
	//echo "<pre>"; print_r($current_user); echo "</pre>";

	$API_VERSION = "0.2";
	
	global $seclog,$log;
	$seclog =& LoggerManager::getLogger('SECURITY');
	$log =& LoggerManager::getLogger('webservice');
	
	function getRequestParamsArrayForOperation($operation){
		global $operationInput;
		return $operationInput[$operation];
	}
	
	function writeErrorOutput($operationManager, $error){
		
		$state = new State();
		$state->success = false;
		$state->error = $error;
		unset($state->result);
		$output = $operationManager->encode($state);
		echo $output;
		
	}
	
	function writeOutput($operationManager, $data){
		
		$state = new State();
		$state->success = true;
		$state->result = $data;
		unset($state->error);
		$output = $operationManager->encode($state);
		echo $output;
		
	}
	
	$operation = vtws_getParameter($_REQUEST, "operation");
	$operation = strtolower($operation);
	$format = vtws_getParameter($_REQUEST, "format","json");
	$sessionId = vtws_getParameter($_REQUEST,"sessionName");
	
	$sessionManager = new SessionManager();
	$operationManager = new OperationManager($adb,$operation,$format,$sessionManager);
	
	try{
		if(!$sessionId || strcasecmp($sessionId,"null")===0){
			$sessionId = null;
		}
		
		$input = $operationManager->getOperationInput();
		$adoptSession = false;
		
		if(strcasecmp($operation,"extendsession")===0){
			if(isset($input['operation'])){
				//echo "<pre>"; print_r($_REQUEST); echo "</pre>";
				$sessionId = vtws_getParameter($_REQUEST,"PHPSESSID");
				$adoptSession = true;
			}else{
				writeErrorOutput($operationManager,new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,"Authencation required"));
				return;
			}
		}
		
		$sid = $sessionManager->startSession($sessionId,$adoptSession);
		//echo $sid;
		if(!$sessionId && !$operationManager->isPreLoginOperation()){
			writeErrorOutput($operationManager,new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,"Authencation required"));
			return;
		}
		
		if(!$sid){
			writeErrorOutput($operationManager, $sessionManager->getError());
			return;
		}
		
		$userid = $sessionManager->get("authenticatedUserId");
		//$userid = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
		//$userid = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);

		require_once('modules/Users/Users.php');
		$current_user = new Users();
		$current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
		//echo $userid; exit;
		/*if($userid){
		
			$seed_user = new Users();
			$current_user = $seed_user->retrieveCurrentUserInfoFromFile($userid);
			
		}else{
			$current_user = null;
		}*/
		
		$operationInput = $operationManager->sanitizeOperation($input);
		$includes = $operationManager->getOperationIncludes();
		
		foreach($includes as $ind=>$path){
			require_once($path);
		}
		//echo "<pre>"; print_r($operationInput); echo "</pre>"; exit;

		$rawOutput = $operationManager->runOperation($operationInput,$current_user);
		writeOutput($operationManager, $rawOutput);
	}catch(WebServiceException $e){
		writeErrorOutput($operationManager,$e);
	}catch(Exception $e){
		writeErrorOutput($operationManager, 
			new WebServiceException(WebServiceErrorCode::$INTERNALERROR,"Unknown Error while processing request"));
	}
?>
