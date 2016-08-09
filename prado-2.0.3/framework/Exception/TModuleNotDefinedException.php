<?php
/**
 * TModuleNotDefinedException class file.
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
 * TModuleNotDefinedException class
 *
 * TModuleNotDefinedException is raised when the framework
 * is unable to locate a module class.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TModuleNotDefinedException extends TException
{
	/**
	 * Constructor.
	 * @param string the module type
	 */
	function __construct($comType)
	{
		parent::__construct("Module type '$comType' is not defined.");
	}
}
?>