<?php
/**
 * TNamespaceInvalidException class file.
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
 * @version $Revision: 1.3 $  $Date: 2005/04/28 20:38:41 $
 * @package System.Exception
 */
 
/**
 * TNamespaceInvalidException class
 *
 * TNamespaceInvalidException is raised when the framework
 * is unable to include a namespace due to nonexisting path/file.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.3 $  $Date: 2005/04/28 20:38:41 $
 * @package System.Exception
 */
class TNamespaceInvalidException extends TException
{
	/**
	 * Constructor.
	 * @param string the namespace
	 */
	function __construct($namespace)
	{
		parent::__construct("Namespace '$namespace' is unable to resolve to a path or file.");
	}
}

?>