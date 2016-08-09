<?php
/**
 * HTTPGlobalization class file.
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
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/01/05 03:15:13 $
 * @package System.I18N
 */

/**
 * Get the HTTP negotitator class.
 */
require_once(dirname(__FILE__).'/core/HTTPNegotiator.php');

/**
 * HTTPGlobalization class.
 * HTTPNegotiator is used to find the prefered language from the 
 * client browser.
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version v1.0, last update on Friday, December 24, 2004
 * @package System.I18N 
 */
class HTTPGlobalization extends TGlobalization
{
	/**
	 * Set the Culture by using the HTTPNegotiator to find
	 * the prefered language from the client browser.
	 */
	function init()
	{
		//initialize other information.
		parent::init();

		$http = new HTTPNegotiator();		
		$languages = $http->getLanguages();

		if(count($languages) > 0)
			$this->Culture = $languages[0];
	}
}

?>