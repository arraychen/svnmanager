<?php
/**
 * TObjectTypeException class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/01/26 05:56:47 $
 * @package System.Exception
 */

/**
 * TObjectTypeException class
 * 
 * TObjectTypeException is raised when an expected object is of wrong type.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Wed Jan 26 16:43:49 EST 2005
 * @package System.Exception
 */
class TObjectTypeException extends TException
{
	
	/**
	 * Constructor.
	 * @param string the expected object type.
	 * @param string the actual object used.
	 */	
	function __construct($expected, $found)
	{
		parent::__construct("Expecting object of type '$expected' but found '$found'.");
	}
}

?>