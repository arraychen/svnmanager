<?php
/**
 * TTemplateNotExistsException class file.
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
 * @version $Revision: 1.3 $  $Date: 2005/01/04 21:33:47 $
 * @package System.Exception
 */
 
/**
 * TTemplateNotExistsException class
 *
 * TTemplateNotExistsException is raised when the framework
 * fails to include an external template file.
 *
 * Namespace: Exception
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.3 $  $Date: 2005/01/04 21:33:47 $
 * @package System.Exception
 */
class TTemplateNotExistsException extends TException
{
	/**
	 * Constructor.
	 * @param string the external template file name
	 */
	function __construct($tplFile)
	{
		parent::__construct("Failed to load external template '$tplFile'.");
	}
}

?>