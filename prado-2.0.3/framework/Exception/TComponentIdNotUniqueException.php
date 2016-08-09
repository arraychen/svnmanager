<?php
/**
 * TComponentIdNotUniqueException class file.
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
 * TComponentIdNotUniqueException class
 *
 * TComponentIdNotUniqueException is raised when the framework
 * detects a component has nonunique ID among its siblings.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:45 $
 * @package System.Exception
 */
class TComponentIdNotUniqueException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type whose children have nonunique ID
	 * @param string the nonunique ID
	 */
	function __construct($comType,$id)
	{
		parent::__construct("Component '$comType' has a nonunique child ID '$id'.");
	}
}
?>