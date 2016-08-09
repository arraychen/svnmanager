<?php
/**
 * TErrorHandler class file.
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
 * @version $Revision: 1.6 $  $Date: 2005/01/04 21:29:39 $
 * @package System
 */
 
/**
 * TErrorHandler class
 *
 * TErrorHandler dispatchs error messages to different error pages.
 * The error pages responsible for different error code can be configured in
 * the application specification using the following format,
 * <code>
 *	<error class="TErrorHandler">
 *		<when error="SiteOff" page="SiteOffPage" />
 *		<when error="Unauthorized" page="Error401Page" />
 *		<when error="Forbidden" page="Error403Page" />
 *		<when error="PageNotFound" page="Error404Page" />
 *		<when error="InternalError" page="Error500Page" />
 *		<otherwise page="xxx" />
 *	</error>
 * </code>
 * where each page attribute refers to a page name.
 *
 * Namespace: System
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TErrorHandler
{
	/**
	 * some predefined error codes.
	 */
	const CASE_SITEOFF='SiteOff';
	const CASE_PAGENOTFOUND='PageNotFound';
	const CASE_UNAUTHORIZED='Unauthorized';
	const CASE_FORBIDDEN='Forbidden';
	const CASE_INTERNALERROR='InternalError';

	/**
	 * list of error handling pages
	 * @var array
	 */
	protected $errorPages=array();
	/**
	 * default page to handle unrecognized error
	 * @var string
	 */
	protected $defaultPage='';
	/**
	 * error message
	 * @var string
	 */
	protected $errorMessage='';
	/**
	 * error code
	 * @var string
	 */
	protected $errorCode='';

	/**
	 * Constructor.
	 * This method reads the configuration given by the application specification.
	 * @param mixed the configuration
	 */
	function __construct($config)
	{
		if(!isset($config->when))
			return;
		foreach($config->when as $when)
		{
			$this->errorPages[(string)$when['error']]=(string)$when['page'];
		}
		$this->defaultPage=isset($config->otherwise['page'])?(string)$config->otherwise['page']:'';
	}

	/**
	 * Dispatches the error handling to a page.
	 * The error page will be executed and the rendering result be displayed.
	 * If the error is not handled by a page, the method will display the error
	 * directly based on the application state (debug or not).
	 * This method always terminates the execution of the current application.
	 * @param string error code
	 * @param string|Exception error message or exception
	 */
	public function handleError($code,$e='')
	{
		static $count=0;
		$debug=pradoGetApplication()->getApplicationState()===TApplication::STATE_DEBUG;
		if($e instanceof Exception)
		{
			if($debug)
				$msg=get_class($e).': '.$e->getMessage()."\n".$e->getTraceAsString();
			else
				$msg=$e->getMessage();
		}
		else
			$msg=$e;
		if($count>0)
		{
			if($debug)
			{
				echo "<h1>Recursive Error</h1>\n";
				echo "<h2>$code</h2>\n";
				echo "<pre>$msg</pre>\n";
				echo "<h2>{$this->errorCode}</h2>\n";
				echo "<pre>{$this->errorMessage}</pre>";
			}
			else
			{
				echo "<h1>$code</h1>\n";
				echo "<pre>$msg</pre>";
			}
		}
		else
		{
			$this->errorCode=$code;
			$this->errorMessage=$msg;
			$count++;
			if(isset($this->errorPages[$code]))
			{
				echo pradoGetApplication()->execute($this->errorPages[$code]);
			}
			else if(!empty($this->defaultPage))
			{
				echo pradoGetApplication()->execute($this->defaultPage);
			}
			else
			{
				echo "<h1>$code</h1>\n";
				echo "<pre>$msg</pre>";
			}
		}
		exit();
	}

	/**
	 * @return string the error code
	 */
	public function getErrorCode()
	{
		return $this->errorCode;
	}

	/**
	 * @return string the error message
	 */
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
}
?>