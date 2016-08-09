<?php
/**
 * TErrorRecursiveException class file.
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
 * TErrorRecursiveException class
 *
 * TErrorRecursiveException is raised when the framework detects a recursive error
 * is reported. (e.g. an error page reports an error!)
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TErrorRecursiveException extends TException
{
	/**
	 * Constructor.
	 * @param string the error code
	 * @param string the error message
	 * @param string the last error code
	 * @param string the last error message
	 */
	function __construct($code,$msg,$lastCode,$lastMsg)
	{
		parent::__construct("Recursive error reported: ($code) $msg. Last error: ($lastCode) $lastMsg.");
	}
}

?>