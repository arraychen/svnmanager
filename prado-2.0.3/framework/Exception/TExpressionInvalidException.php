<?php
/**
 * TExpressionInvalidException class file.
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
 * TExpressionInvalidException class
 *
 * TExpressionInvalidException is raised when the framework
 * detects an error happened during executing a PHP expression.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TExpressionInvalidException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type or ID
	 * @param string the expression
	 * @param string the error message
	 */
	function __construct($comType,$expression,$msg='')
	{
		if(empty($msg))
			parent::__construct("Component '$comType' executes an invalid expression '$expression'.");
		else
			parent::__construct("Component '$comType' executes an invalid expression '$expression': $msg.");
	}
}

?>