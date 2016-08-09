<?php
/**
 * TService_Callback class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:weizhuo[at]gmail[dot]com Wei Zhuo}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.5 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.Services
 */

/**
 * Include the callback server.
 */
require_once(dirname(__FILE__).'/AJAX/TCallbackServer.php');

/**
 * Callback service handler.
 *
 * Encapsulates the callback server as a service.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.5 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.Services
 */
class TService_Callback extends TService
{		
	/**
	 * Callback server
	 * @var TCallbackServer
	 */
	protected $server;
	
	/**
	 * Callback requests are to be executed within a page.
	 * @return boolean always false.
	 */
	function IsRequestServiceable($request)
	{
		return false;
	}	
	
	/**
	 * Create a new Callack server.
	 */
	function __construct()
	{
		$this->server = new TCallbackServer();
	}
	
	/**
	 * Returns the current Callback server.
	 * @return TCallbackServer
	 */
	public function server()
	{
		return $this->server;
	}
	
	/**
	 * Handle the callback request.
	 */
	function execute()
	{
		$this->server->handleRequest();
	}	
	
	/**
	 * Register callback objects.
	 * @param string|object the page that handles the callback request
	 */
	function register($object)
	{
		$this->server->register($object);
	}
	
	/**
	 * Returns true if a valid callback request
	 * @return boolean true if valid callback, false otherwise
	 */
	function isCallback()
	{
		return $this->server->isCallbackRequest();
	}
	
	/**
	 * Returns true if a AJAX request, including javascript code requests.
	 * @return boolean true if AJAX request.
	 */
	function isServiceRequest()
	{
		return $this->server->getUri()->isServerRequest();
	}
	
	/**
	 * Gets the control ID that handles the callback event.
	 * @return string callback handler control ID.
	 */
	public function getRequestID()
	{
		return $this->server->getRequest()->getRequestID();
	}
	
	/**
	 * Loads callback post data into $_REQUEST.

	 */
	public function loadCallBackPostData()
	{
		$this->server->getRequest()->loadCallBackPostData();
	}
	
	/**
	 * Gets the client javascript source URL.	 
	 * @return string client-side javascript source URL
	 */
	public function getClientUri()
	{
		return $this->server->getJsSrc();
	}
}

?>