<?php

/**
 * I18N Example.
 * @author $Author: weizhuo $
 * @version $Id: MyHTTPGlobalization.php,v 1.2 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * MyHTTPGlobalization class.
 * 
 * Custom Globalization class, using URL as well as the client
 * browser to determine the requested language choice.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Tue Dec 28 17:47:32 EST 2004
 * @package prado.examples
 */
class MyHTTPGlobalization extends HTTPGlobalization 
{
	/**
	 * Set the Culture by using the GET URL.
	 */
	function init()
	{
		//initialize other information.
		parent::init();

		$cookiename = 'I18N_Example_lang';
		
		$culture = null;
		
		//go for the GET URL
		if (isset($_GET['lang']))
			$culture = $_GET['lang'];
	
		//set the culture if valid.
		if(!empty($culture) && CultureInfo::validCulture($culture))
			$this->Culture = $culture;			
	}	
}
?>