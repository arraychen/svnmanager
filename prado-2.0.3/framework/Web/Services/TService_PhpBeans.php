<?php
/**
 * TService_PhpBeans class file.
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
 * @version $Revision: 1.3 $  $Date: 2005/04/14 15:25:49 $
 * @package System.Web.Services
 */

require_once(dirname(__FILE__).'/PhpBeans/phpbeans.php');

/**
 * TService_PhpBeans class
 *
 * Allows PhpBean requests
 *
 * @author Jason Ragsdale <jrags[at]jasrags.new>
 * @version v1.0, last update on 2005/03/11 21:44:52
 * @package System.Web.Services
 */
class TService_PhpBeans extends TService
{
	const service = '__PhpBeans';

	protected $server;
	protected $host;
	protected $user;
	protected $pass;
	protected $port;
	protected $timeout = null;
	protected $serverclass = 'PHP_Bean_Client';
	protected $objectCache = array();
	protected $classes = array();

	function __construct($config)
	{
		$this->host = isset($config['host']) ? (string)$config['host'] : null;
		$this->port = isset($config['port']) ? (int)$config['port'] : null;
		$this->timeout = isset($config['timeout']) ? (int)$config['timeout'] : null;

		if(isset($config['user']))
			$this->user = (string)$config['user'];
		else
			throw new TPhpBeanException('attribute "user" must be specified in a PhpBean service');
		
		if(isset($config['pass']))
			$this->pass = (string)$config['pass'];
		else
			throw new TPhpBeanException('attribute "pass" must be specified in a PhpBean service');	

		if(isset($config['class']))
			$this->serverclass = (string)$config['class'];

		//Load each of the bean classes.
		foreach($config->class as $class)
			$this->classes[] = (string)$class['name'];
	}

	function __destruct()
	{
		$this->disconnect();
	}

	protected function connect()
	{
		$server = $this->serverclass;
		$this->server = new $server($this->host, $this->port, $this->timeout);

		if (!$this->server->connect())
		{
			throw new TPhpBeanException($this->server->error);
		}

		$this->autenticate();

	}

	protected function disconnect()
	{
		//Disconnect after we load our beans.
		if(!is_null($this->server))
			$this->server->disconnect();
	}

	protected function autenticate()
	{
		//Authenticate
		if (!$this->server->authenticate($this->user, $this->pass))
		{
			throw new TPhpBeanException($this->server->error);
		}
	}

	public function load($class)
	{
		if(isset($this->objectCache[$class]))
			return $this->objectCache[$class];
		
		if(is_null($this->server)) $this->connect();

		$object = $this->server->getObject($class);
		if($object === false)
			throw new TPhpBeanObjectNotFoundException("Error in load phpbean object {$class} : ".
									$this->server->error);
		$this->objectCache[$class] = $object;
		return $object;
	}

	public function isObjectDefined($object)
	{
		return in_array($object, $this->classes);
	}

	function IsRequestServiceable($request)
	{
		return false;
	}

	function execute()
	{
		throw new TPhpBeanException('PhpBean can not be executed directly');
	}
}

class PhpBeanFinder
{
	public static function find($services, $object)
	{
		if(!is_array($services))
				$services = array($services); //make it an array
		foreach($services as $service)
		{
			if($service->isObjectDefined($object))
				return $service->load($object);
		}
		throw new TPhpBeanUndefinedObjectException();
	}
}

class TPhpBeanException extends TException
{
}

class TPhpBeanObjectNotFoundException extends TPhpBeanException
{

}
class TPhpBeanUndefinedObjectException extends TPhpBeanException
{

}

?>