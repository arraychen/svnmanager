<?php
/**
 * TUnexpectedException class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:48 $
 * @package System.Exception
 */
 
/**
 * TUnexpectedException class
 *
 * TUnexpectedException is raised when an unexpected exception is raised.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:48 $
 * @package System.Exception
 */
class TUnexpectedException extends TException
{
	/**
	 * Constructor.
	 * @param string the error message
	 */
	function __construct($msg)
	{
		parent::__construct("An unexpected error happened: $msg. Please report this problem to PRADO developer team. Thanks.");
	}
}

?>