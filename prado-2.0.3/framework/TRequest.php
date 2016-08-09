<?php
/**
 * TRequest class file.
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
 * @version $Revision: 1.13 $  $Date: 2005/11/06 23:02:33 $
 * @package System
 */

/**
 * TRequest class
 *
 * TRequest encapsulates the user request data. It is also responsible
 * for interpretting URL parameters.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TRequest
{
	/**
	 * page service name
	 */
	const PAGE_SERVICE='page';
	/**
	 * URL format
	 */
	const FORMAT_GET=0;
	const FORMAT_PATH=1;
	/**
	 * GET and POST parameters
	 * @var array
	 */
	protected $parameters;
	/**
	 * User requested page name (pageType or ModuleID.pageType)
	 * @var string
	 */
	protected $requestedPage;
	/**
	 * Default page name defined in app spec
	 * @var string
	 */
	protected $defaultPage;
	/**
	 * The format of URL
	 * @var integer
	 */
	protected $format=0;

	/**
	 * Constructor.
	 * Parses the configuration given in app spec.
	 * @param mixed the configuration
	 */
	function __construct($config)
	{
		if(isset($config['format']) && $config['format']=='path')
			$this->format=self::FORMAT_PATH;
		else
			$this->format=self::FORMAT_GET;
		$this->defaultPage=isset($config['default'])?(string)$config['default']:'';
		if($this->format==self::FORMAT_GET)
			$this->parameters=array_merge($_POST,$_GET);
		else
		{
			$this->parameters=$_POST;
			$requestURI=$_SERVER['PHP_SELF']; //note: REQUEST_URI will not reflect Rewrite rules
			$script=$_SERVER['SCRIPT_NAME'];
			if(strpos($requestURI,$script)===0)
			{
				$paths=explode('/',trim(substr($requestURI,strlen($script)),'/'));
				$n=count($paths);
				for($i=0;$i<$n;++$i)
				{
					if($i+1<$n)
						$this->parameters[$paths[$i]]=$paths[++$i];
				}
			}
			else
				throw new TUnexpectedException("Failed to extract request path info.");
		}
		$this->requestedPage=isset($this->parameters[self::PAGE_SERVICE])?$this->parameters[self::PAGE_SERVICE]:$this->defaultPage;
	}

	/**
	 * Constructs a PRADO specific URL.
	 * @param string|null page name (pageType or moduleID.pageType), null if default page should be used
	 * @param array|null GET parameters (name=>value pairs), null if no GET parameters
	 * @return string the corresponding URL recoganized by PRADO
	 */
	public function constructUrl($pageName=null,$getParameters=null)
	{
		if(empty($pageName))
			$pageName=$this->getDefaultPage();
		$url=$_SERVER['SCRIPT_NAME'];
		if($this->format==self::FORMAT_GET)
		{
			$url.='?'.self::PAGE_SERVICE.'='.$pageName;
			if(is_array($getParameters))
			{
				foreach($getParameters as $name=>$value)
					$url.='&'.urlencode($name).'='.urlencode($value);
			}
			if(defined('SID') && SID != '')
				$url.='&'.SID;
		}
		else
		{
			$url.='/'.self::PAGE_SERVICE.'/'.$pageName;
			if(is_array($getParameters))
			{
				foreach($getParameters as $name=>$value)
					$url.='/'.urlencode($name).'/'.urlencode($value);
			}
			if(defined('SID') && SID != '')
				$url.='?'.SID;
		}
		return $url;

	}

	/**
	 * Returns the path format of request URLs
	 * @return boolean path format
	 */
	public function getFormat()
	{
		return $this->format;
	}
	
	/**
	 * @return string the requested page name (pageType or moduleID.pageType)
	 */
	public function getRequestedPage()
	{
		return $this->requestedPage;
	}

	/**
	 * @return array the whole list of GET and POST variables
	 */
	public function &getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @return string|null the named GET or POST variable, null if not present.
	 */
	public function getParameter($name)
	{
		return isset($this->parameters[$name])?$this->parameters[$name]:null;
	}

	/**
	 * @return string the directory that contains the entry script file
	 */
	public function getDocumentRoot()
	{
		return dirname($_SERVER['SCRIPT_FILE_NAME']);
	}

	/**
	 * @return string the default page name defined in app spec.
	 */
	public function getDefaultPage()
	{
		return $this->defaultPage;
	}
}

?>