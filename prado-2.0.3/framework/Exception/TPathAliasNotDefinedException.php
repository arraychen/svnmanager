<?php
/**
 * TPathAliasNotDefinedException class file.
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
 * @version $Revision: 1.3 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
 
/**
 * TPathAliasNotDefinedException class
 *
 * TPathAliasNotDefinedException is raised when the framework
 * fails to use a namespace containing unknown path alias.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.3 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TPathAliasNotDefinedException extends TException
{
	/**
	 * Constructor.
	 * @param string the path alias to be translated
	 */
	function __construct($alias)
	{
		parent::__construct("Unable to translate the path alias '$alias'.");
	}
}

?>