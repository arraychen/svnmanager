<?php
/**
 * TTemplateParsingFailedException class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:47 $
 * @package System.Exception
 */
 
/**
 * TTemplateParsingFailedException class
 *
 * TTemplateParsingFailedException is raised when the framework
 * fails in parsing a component template file.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.2 $  $Date: 2005/01/04 21:33:47 $
 * @package System.Exception
 */
class TTemplateParsingFailedException extends TException
{
	/**
	 * Constructor.
	 * @param string the component template file name
	 * @param string the parsing error message
	 */
	function __construct($tplFile,$msg='')
	{
		if(strlen($msg))
			parent::__construct("Failed to parse template file '$tplFile': $msg");
		else
			parent::__construct("Failed to parse template file '$tplFile'.");
	}
}

?>