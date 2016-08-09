<?php

/**
 * TPropertyValueInvalidException class file.
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
 * @version $Revision: 1.1 $  $Date: 2005/01/15 03:38:50 $
 * @package System.Exception
 */
 
/**
 * TPropertyValueInvalidException class
 *
 * TPropertyValueInvalidException is raised when the framework
 * detects a component property is configured with an invalid formatted string.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.1 $  $Date: 2005/01/15 03:38:50 $
 * @package System.Exception
 */
class TPropertyValueInvalidException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type
	 * @param string the property name
	 * @param string the property value
	 */
	function __construct($comType,$propName,$value)
	{
		parent::__construct("Property '$comType.$propName' is configured with an invalid value '$value'.");
	}
}
?>