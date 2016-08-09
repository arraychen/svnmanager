<?php
/**
 * TSevice class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:weizhuo[at]gmail[dot]com Wei Zhuo}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.Services
 */

/**
 * Abstract Service class.
 *
 * Service providers must extend TService.
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.Services
 */
abstract class TService
{
	/**
	 * Find the class name from e,g. "My.NameSpace.MyClass" and returns "MyClass".
	 * In addition, namespace are imported.
	 * @param string class path.
	 * @return string class name
	 */
	protected function findClass($classpath)
	{
		if(strpos($classpath, '.') !== false)
		{
			using($classpath);
			$class = explode('.', $classpath);
			return $class[count($class)-1];
		}
		else
			return $classpath;
	}
	
	/**
	 * Determine from the current URI if this service should server the request.
	 * @param string service name.
	 * @return boolean true if able to service the request, false otherwise.
	 */
	abstract function IsRequestServiceable($request);
	
	/**
	 * Execute the service.
	 */
	abstract function execute();
	
	/**
	 * Return the service client URI. e.g. the URI to access the service.
	 * @return string service URI.
	 */
	abstract function getClientUri();
}