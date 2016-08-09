<?php
/**
 * TService_AJAX class file.
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
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version $Revision: 1.1 $  $Date: 2005/03/11 05:12:27 $
 * @package System.Web.Services
 */

/**
 * TService_XMLRPC class
 *
 * Allows XML-RPC requests.
 *
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on 2005/03/11 21:44:52
 * @package System.Web.Services
 */
class TService_XMLRPC
{
	const service = '__XMLRPC';
	
	function __construct($config)
	{
		
	}
	
	function IsRequestServiceable($request)
	{
		return isset($request[self::service]);
	}
	
	function execute()
	{
		var_dump('no XML-RPC for you today.');
	}		
}

?>