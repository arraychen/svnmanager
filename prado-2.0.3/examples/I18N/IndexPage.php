<?php
/**
 * Project description.
 * @author $Author: weizhuo $
 * @version $Id: IndexPage.php,v 1.7 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * IndexPage class.
 * 
 * I18N Example index page.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Tue Dec 28 17:58:46 EST 2004
 * @package prado.examples
 */
class IndexPage extends TPage 
{
	/**
	 * Initialize the page with some arbituary data.
	 * @param TEventParameter event parameter.
	 */	
	function onLoad($param) 
	{
		$time1 = $this->Time1;
		$time1->Value = time();

		$number2 = $this->Number2;
		$number2->Value = 46412.416;

		$this->dataBind();
	}

	/**
	 * Get the current culture code.
	 * @return string culture code, e.g. en_AU 
	 */
	function getCulture()
	{
		$app = $this->Application->getGlobalization();
		
		return $app->Culture;
	}

	/**
	 * Get the localized current culture name.
	 * @return string localized curreny culture name. 
	 */
	function cultureName() 
	{
		$culture = $this->getCulture();
		$cultureInfo = new CultureInfo($culture);
		return $cultureInfo->NativeName;
	}
}

?>