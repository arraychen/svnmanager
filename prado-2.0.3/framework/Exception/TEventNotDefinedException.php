<?php
/**
 * TEventNotDefinedException class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
 
/**
 * TEventNotDefinedException class
 *
 * TEventNotDefinedException is raised when the framework
 * detects the usage of some undefined component event.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TEventNotDefinedException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type
	 * @param string the undefined event name
	 */
	function __construct($comType,$eventName)
	{
		parent::__construct("Event '$comType.$eventName' is not defined.");
	}
}

?>