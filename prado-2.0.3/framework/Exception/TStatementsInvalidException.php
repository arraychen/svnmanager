<?php
/**
 * TStatementsInvalidException class file.
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
 * TStatementsInvalidException class
 *
 * TStatementsInvalidException is raised when the framework
 * detects an error happened during executing a block of PHP statements.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TStatementsInvalidException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type or ID
	 * @param string the error message
	 */
	function __construct($comType,$msg='')
	{
		if(empty($msg))
			parent::__construct("Component '$comType' has problem in executing PHP statements.");
		else
			parent::__construct("Component '$comType' has problem in executing PHP statements: $msg.");
	}
}

?>