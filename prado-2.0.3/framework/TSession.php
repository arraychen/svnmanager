<?php
/**
 * TSession class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Qiang Xue. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.6 $  $Date: 2005/04/29 22:25:23 $
 * @package System
 */

/**
 * TSession class
 *
 * TSession class is an implementation of ISession interface based on $_SESSION.
 *
 * TSesson simply encapsulates what you can do with $_SESSION.
 * You may want to extend this class or simply implement directly ISession interface
 * to provide your own session handling classes (e.g.: you may want to use DB
 * to store session data.)
 *
 * Namespace: System.Security
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/10/24 12:00:00
 * @package System
 */
class TSession implements ISession
{
	/**
	 * Whether the session is started.
	 * @var boolean
	 */
	protected $started=false;
	/**
	 * Whether the session is enabled.
	 * @var boolean
	 */
	protected $enabled=false;
	/**
	 * Cache expire (in minutes)
	 * @var integer
	 */
	protected $cacheExpire=0;
	/**
	 * The path used to save the session files
	 * @var string
	 */
	protected $savePath='';

	/**
	 * Constructor.
	 * Parses the configuration passed from application specification.
	 * Sets the value indicating whether to start session.
	 * @param mixed the configuration data.
	 */
	function __construct($config)
	{
		if(isset($config['enabled']) && (string)$config['enabled']=='true')
			$this->enabled=true;
		else
			$this->enabled=false;
		if(isset($config['cache-expire']))
			$this->cacheExpire=intval((string)$config['cache-expire']);
		if(isset($config['save-path']))
			$this->savePath=(string)$config['save-path'];
	}

	/**
	 * Checks if the named session variable exists.
	 * @return boolean whether the named session variable exists
	 */
	public function has($name)
	{
		return isset($_SESSION[$name]);
	}

	/**
	 * Returns the value of the named session variable
	 * @param the name of the session variable
	 * @return mixed the value of the session variable
	 */
	public function get($name)
	{
		return isset($_SESSION[$name])?$_SESSION[$name]:null;
	}

	/**
	 * Sets a session variable
	 * @param string the session variable name
	 * @param mixed the variable value. If the value is null, the corresponding session variable will be cleared.
	 */
	public function set($name,$value)
	{
		if(strlen($name))
		{
			if(is_null($value))
				unset($_SESSION[$name]);
			else
				$_SESSION[$name]=$value;
		}
	}

	/**
	 * Unsets a session variable.
	 * @param string the session variable name
	 */
	public function clear($name)
	{
		if(isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}

	/**
	 * Starts the session.
	 */
	public function start()
	{
		if($this->enabled)
		{
			if($this->cacheExpire>0)
				session_cache_expire($this->cacheExpire);
			if(!empty($this->savePath) && is_dir($this->savePath))
				session_save_path($this->savePath);
			if(!session_id())
				session_start();
			$this->started=true;
		}
	}

	/**
	 * Destroys the session.
	 */
	public function destroy()
	{
		$this->started=false;
		$_SESSION=array();
		session_destroy();
	}

	/**
	 * @return boolean whether the session is started
	 */
	public function isStarted()
	{
		return $this->started;
	}

	/**
	 * @return string session ID
	 */
	public function getSessionID()
	{
		return session_id();
	}
}
?>