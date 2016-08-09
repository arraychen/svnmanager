<?php
/**
 * ThemeParsingFailedException class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Alex Flint. All rights reserved.
 *
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Alex Flint <alex[at]linium[dot]net>
 * @version $Revision: 1.1 $  $Date: 2005/05/07 06:09:27 $
 * @package System.Exception
 */
 
/**
 * TThemeParsingFailedException class
 *
 * TThemeParsingFailedException is raised when the framework
 * fails in parsing a theme file.
 *
 * Namespace: Exception
 *
 * @author Alex Flint <alex[at]linium[dot]net>
 * @version $Revision: 1.1 $  $Date: 2005/05/07 06:09:27 $
 * @package System.Exception
 */
class TThemeParsingFailedException extends TException
{
	/**
	 * Constructor.
	 * @param string the component theme file name
	 * @param string the parsing error message
	 */
	function __construct($themeFile,$msg='')
	{
		if(strlen($msg))
			parent::__construct("Failed to parse theme file '$themeFile': $msg");
		else
			parent::__construct("Failed to parse theme file '$themeFile'.");
	}
}

?>