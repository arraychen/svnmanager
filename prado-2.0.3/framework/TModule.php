<?php
/**
 * TModule class file.
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
 * @version $Revision: 1.5 $  $Date: 2005/01/04 21:29:41 $
 * @package System
 */

/**
 * TModule class
 *
 * TModule provides a way for organizing pages and sharing data among them.
 *
 * TModule mainly introduces the concept of namespace partition.
 * TModule cannot be rendered. It serves as the parent of data components such as
 * DB components and logic components such as actions.
 * So TModule may be considered a place for centralizing model and control
 * while pages are more oriented to presentation.
 *
 * Namespace: System
 *
 * Events
 * - <b>OnLoad</b>, Occurs right before the requested member page starts execution.
 * - <b>OnUnload</b>, Occurs right after the requested member page finishes execution.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TModule extends TComponent
{
	/**
	 * module level user parameters
	 * @var array
	 */
	protected $parameters=array();

	/**
	 * Constructor.
	 */
	function __construct()
	{
		$this->setRoot($this);
		parent::__construct();
	}

	/**
	 * Reads the configuration provided in the app spec.
	 * This method is automatically invoked by the framework after the module is created.
	 * @param mixed the configuration
	 */
	public function loadConfig($config)
	{
	}

	/**
	 * @param string the parameter name
	 * @return mixed the parameter value, null if parameter doesn't exist.
	 */
	public function getUserParameter($name)
	{
		return isset($this->parameters[$name])?$this->parameters[$name]:null;
	}

	/**
	 * Sets a user parameter.
	 * If the value is null, the corresponding parameter will be cleared.
	 * @param string the parameter name
	 * @param mixed the parameter value
	 */
	public function setUserParameter($name,$value)
	{
		if(is_null($value))
			unset($this->parameters[$name]);
		else
		{
			if(!preg_match("/^[a-zA-Z]\\w*\$/",$name))
				throw new Exception("Parameter name '$name' is invalid.");
			$this->parameters[$name]=$value;
		}
	}

	/**
	 * Sets a list of user parameters.
	 * @param array list of parameters (name=>value pairs)
	 */
	public function setUserParameters($params)
	{
		$this->parameters=$params;
	}

	/**
	 * @return array list of user parameters (name=>value pairs)
	 */
	public function getUserParameters()
	{
		return $this->parameters;
	}

	/**
	 * This method is invoked right before a member page starts execution.
	 * The method raises 'OnLoad' event.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event handlers are invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onLoad($param)
	{
		$this->raiseEvent('OnLoad',$this,$param);
	}

	/**
	 * This method is invoked right after a member page finishes execution.
	 * The method raises 'OnUnload' event.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event handlers are invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onUnload($param)
	{
		$this->raiseEvent('OnUnload',$this,$param);
	}
}

?>