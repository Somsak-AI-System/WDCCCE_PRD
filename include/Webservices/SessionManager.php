<?php
session_start();
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

	require_once("include/HTTP_Session/Session.php");
	// Later may we can move this to config file.
	
	global $maxWebServiceSessionLifeSpan, $maxWebServiceSessionIdleTime;
	
	$maxWebServiceSessionLifeSpan = 86400; //Max life span of a session is a day.
	$maxWebServiceSessionIdleTime = 1800; //Max life span session should be kept alive after the last transaction.
	
	// Till Here.
	
	class SessionManager{
		private $maxLife ;  
		private $idleLife ;
		//Note: the url lookup part of http_session will have String null or this be used as id instead of ignoring it.
		//private $sessionName = "sessionName";
		private $sessionVar = "__SessionExists";
		private $error ;
		
		function SessionManager(){
			
			global $maxWebServiceSessionLifeSpan, $maxWebServiceSessionIdleTime;
			
			$now = time();
			$this->maxLife = $now + $maxWebServiceSessionLifeSpan;
			$this->idleLife = $now + $maxWebServiceSessionIdleTime;
			
			HTTP_Session::useCookies(false); //disable cookie usage. may this could be moved out constructor?
			// only first invocation of following method, which is setExpire 
			//have an effect and any further invocation will be have no effect.
			HTTP_Session::setExpire($this->maxLife);
			// this method replaces the new with old time if second params is true 
			//otherwise it subtracts the time from previous time
			HTTP_Session::setIdle($this->idleLife, true);
		}
		
		function isValid(){
			
			$valid = true;
			// expired
			if (HTTP_Session::isExpired()) {
				$valid = false;
				//echo 3333; exit;
				HTTP_Session::destroy();
				throw new WebServiceException(WebServiceErrorCode::$SESSLIFEOVER,"Session has life span over please login again");
			}
			
			// idled
			if (HTTP_Session::isIdle()) {
				$valid = false;
				//echo 4444; exit;
				HTTP_Session::destroy();
				throw new WebServiceException(WebServiceErrorCode::$SESSIONIDLE,"Session has been invalidated to due lack activity");
			}
			//echo "<br>is new: ", HTTP_Session::isNew();
			//invalid sessionId provided.
			//echo "<br>get: ",$this->get($this->sessionVar);
			//echo "<pre>"; print_r($this); echo "</pre>";
			if(!$this->get($this->sessionVar) && !HTTP_Session::isNew()){
				$valid = false;
				//echo 5555; exit;
				HTTP_Session::destroy();
				throw new WebServiceException(WebServiceErrorCode::$SESSIONIDINVALID,"Session Identifier provided is Invalid");
			}
			
			return $valid;
		}
		
		function startSession($sid = null,$adoptSession=false){
			
//			if($sid){
//				HTTP_Session::id($sid);
//			}
			$adoptSession=true;
			//echo $adoptSession; exit;
			if(!$sid || strlen($sid) ===0){
				$sid = null;
			}
			
			//session name is used for guessing the session id by http_session so pass null.
			HTTP_Session::start(null, $sid);
			
			$newSID = HTTP_Session::id();
			//echo "<pre>"; print_r($newSID); echo "</pre>"; exit;
			//echo "<pre>"; print_r($this->get($this->sessionVar)); echo "</pre>"; exit;
			
			if(!$sid || $adoptSession==true){
				$this->set($this->sessionVar,"true");
			}else{
				//session_start();
				if(!$this->get($this->sessionVar)){
					//HTTP_Session::destroy();
					throw new WebServiceException(WebServiceErrorCode::$SESSIONIDINVALID,"Session Identifier provided is Invalid");
					$newSID = null;
				}
			}
			
			if(!$this->isValid()){
				$newSID = null;
			}
			$sid = $newSID;
			return $sid;
			
		}
		
		function getSessionId(){
			return HTTP_Session::id();
		}
		
		function set($var_name, $var_value){
			//TODO test setRef and getRef combination
			//echo "<br>setting name: ",$var_name," :value: ",$var_value;
			HTTP_Session::set($var_name, $var_value);
		}
		
		function get($name){
			//echo "<br> getting for: ",$name," :value: ",HTTP_Session::get($name);
			return HTTP_Session::get($name);
		}
		
		FUNCTION getError(){
			return $this->error;
		}
		
		function destroy(){
			HTTP_Session::destroy();
		}
		
	}
	
?>
