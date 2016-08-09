<?php
/**
 * TPropertyGetterInvalidException class file.
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
 * TPropertyGetterInvalidException class
 *
 * TPropertyInvalidGetterException is raised when the framework
 * detects that a property is defined with an invalid getter method.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TPropertyGetterInvalidException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type
	 * @param string the property name
	 * @param string the getter method
	 */
	function __construct($comType,$propName,$getter)
	{
		parent::__construct("Property '$comType.$propName' is defined with an invalid getter method '$getter'.");
	}
}

?>