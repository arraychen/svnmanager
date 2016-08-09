<?php
/**
 * TServiceManager class file.
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
 * @package System
 */

/**
 * TServiceManager class
 *
 * The ServiceManager is responsible for handling and dispatching all 
 * web services to their respective classes. Once the Service manager 
 * instantiated with XML config, call handleRequest() to service the request.
 * The function handleRequest() will return true if service was handled, 
 * false otherwise.
 *
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on 2005/03/11 21:44:52
 * @package System
 */
class TServiceManager 
{	
	/**
	 * A list of all the service instantiated.
	 * @var array
	 */
	protected $services = array();
	
	/**
	 * Constructor.
	 * @param SimpleXML configuration.
	 */
	public function __construct($config)
	{
		if(!empty($config->service))
		{
			foreach($config->service as $service)
			{			
				if(empty($service['type']))
					throw new TException('Attribute "type" must be set for each service');
				$type = (string)$service['type'];
				$classname = 'TService_'.$type;
				$this->addService($type, new $classname($service));
			}
		}
	}
	
	/**
	 * Add a new service.
	 * @param string service type, generally the service class name.
	 * @param TService a new service.
	 */
	public function addService($type, $service)
	{
		$this->services[$type][] = $service;
	}
	
	/**
	 * Return a list of service objects for the given service type.
	 * @param string service type.
	 * @return TService[] list of services. 
	 */
	public function getServices($type)
	{
		if(isset($this->services[$type]))
			return $this->services[$type];
	}
	
	/**
	 * Execute the service for this particular type.
	 * @param string service type.
	 */
	public function execute($type)
	{
		if(isset($this->services[$type]))
		{
			foreach($this->services[$type] as $service)
			{
				$service->execute();
			}
		}
	}
	
	/**
	 * Check if the request can be handled, and calls the respective service
	 * instantances to handle the request.
	 * @return boolean true if the request was handled, false otherewise.
	 */
	public function handleRequest()
	{
		if(($type = $this->executable($_REQUEST)) !== false)
		{
			$this->execute($type);
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Check if the request can be handled.
	 * @param array request parameters
	 * @return boolean true if can be handled, false otherwise.
	 */
	protected function executable($request)
	{
		foreach($this->services as $type => $services)
		{
			foreach($services as $service)
				if($service->IsRequestServiceable($request))
					return $type;
		}
		return false;
	}
}

/**
 * AJAX components must implement ICallbackEventHandler.
 * 
 * The <b>raiseCallbackEvent($args)</b> is called when an AJAX request is made.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Tue May 03 20:22:20 EST 2005
 * @package System.Web.Services.AJAX
 */
interface ICallbackEventHandler
{
	/**
	 * Called when an Ajax is requested.
	 * @param TCallbackEventParameter arguments passed during callback.
	 * @return mixed the data return back to the javascript caller.
	 */
	public function raiseCallbackEvent($eventArgument);
}

?>