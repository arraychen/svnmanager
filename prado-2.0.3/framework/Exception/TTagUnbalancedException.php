<?php
/**
 * TTagUnbalancedException class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:46 $
 * @package System.Exception
 */
 
/**
 * TTagUnbalancedException class
 *
 * TTagUnbalancedException is raised when the framework
 * detects an unbalanced tag in a template.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:46 $
 * @package System.Exception
 */
class TTagUnbalancedException extends TException
{
	/**
	 * Constructor.
	 * @param string the tag name.
	 */
	function __construct($tagName)
	{
		parent::__construct("Tag '$tagName' is not balanced.");
	}
}

?>