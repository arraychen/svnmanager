<?php
/**
 * TService_AJAX class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version $Revision: 1.5 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.Services
 */

require_once(dirname(__FILE__).'/AJAX/TRemoteObjectServer.php');

/**
 * TService_AJAX class
 *
 * Allows XMLHTTPRequests for arbituary classes.
 * 
 * This service should be initialized from the application.spec file, e.g.
 * <pre>
 * <services>
 *    <service type="AJAX">
 *       <class name="HelloService" />
 *    </service>
 * </services> 
 * </pre>
 *
 * The methods in the service classes must have a @webservice
 * doc-comment property. In addition, these classes must have a 
 * default constructor. For example, 
 * <code>
 * class HelloService
 * {
 *     /**
 *      * @webservice
 *      * /
 *     public function sayHello($name)
 *     {
 *          return "Hello {$name}";
 *     }
 * }
 * </code>
 *
 * To call the methods of the these class from the client javascript side, 
 * do the following.
 * <code>
 * <script type="text/javascript">
 * //<![CDATA[
 *  var response = 
 *  {
 *      sayHello : function(result, output)
 *      {
 *          alert(result);
 *      }
 *  }
 *
 *  var helloService = new HelloService(response);
 *  helloService.sayHello("Wei!"); //call remote method in async mode
 *
 *  //]]>
 * </script>
 * </code>
 *
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on 2005/03/11 21:44:52
 * @package System.Web.Services
 */
class TService_AJAX extends TService
{
	/**
	 * AJAX remote object server.
	 * @var TRemoteObjectServer
	 */
	protected $server;
	
	/**
	 * Initialize the AJAX remote object class from configuration.
	 * @param xml configuration.
	 */
	function __construct($config)
	{
		$serverclass = 'TRemoteObjectServer';
		if(isset($config['class'])) $serverclass = (string)$config['class'];
		
		$this->server = new $serverclass();

		foreach($config->class as $class)
		{
			$classname = $this->findClass((string)$class['name']);
			$this->server->register(new $classname);
		}
	}
	
	/**
	 * Determine if the service is applicable.
	 * @param string request type
	 * @return boolean true if able to service remote object request
	 */
	function IsRequestServiceable($request)
	{
		return $this->server->getUri()->isServerRequest();
	}
	
	/**
	 * Execute the service, let the server handle errors and exceptions.
	 */
	function execute()
	{
		set_error_handler(array($this->server, 'handleError'));
		set_exception_handler(array($this->server, 'handleException'));	
		$this->server->handleRequest();
		restore_error_handler();
		restore_exception_handler();
	}
	
	/**
	 * Get the client-side javascript code URL.
	 * @return string client-side javascript source URL.
	 */
	public function getClientUri()
	{
		return $this->server->getJsSrc();
	}
}

?>