<?php
/**
 * TDuplicateListValueException class file.
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
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.1 $  $Date: 2005/01/12 09:55:33 $
 * @package System.Exception
 */
 
/**
 * TDuplicateListValueException class
 *
 * TDuplicateListValueException is raised when the framework
 * detects duplicate Value properties for list items in a list control
 *
 * Namespace: Exception
 *
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.1 $  $Date: 2005/01/12 09:55:33 $
 * @package System.Exception
 */
class TDuplicateListValueException extends TException
{
	/**
	 * Constructor.
	 * @param string the Value of the list item.
	 * @param string the list control the value was in.
	 */
	function __construct($value, $control)
	{
		$obj = new TListBox();
		parent::__construct("Duplicate Value property '$value' found for control '$control'.");
	}
}
?>