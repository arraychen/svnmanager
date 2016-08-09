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
 * @author Alex Flint <alex[at]linium[dor]net>
 * @version $Revision: 1.1 $  $Date: 2005/05/23 12:04:57 $
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
 * @author Alex Flint <alex[at]linium[dor]net>
 * @version $Revision: 1.1 $  $Date: 2005/05/23 12:04:57 $
 * @package System.Exception
 */
class TComponentIdNameClashException extends TException
{
	/**
	 * Constructor.
	 * @param string the component type whose children have nonunique ID
	 * @param string the nonunique ID
	 */
	function __construct($comType,$id)
	{
		parent::__construct("Component '$comType' has a child with ID '$id' which clashes with a member variable of the same name.");
	}
}
?>