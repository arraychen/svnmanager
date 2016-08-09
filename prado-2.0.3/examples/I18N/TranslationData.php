<?php
/**
 * I18N Example.
 * @author $Author: weizhuo $
 * @version $Id: TranslationData.php,v 1.4 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * Translation Data Module
 * 
 * Holds the settings for the Translation Editor.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Tue Dec 28 17:47:32 EST 2004
 * @package prado.examples
 */
class TranslationData extends TModule 
{
	/**
	 * Get and set the language setting from/to the cookie.
	 * @param TEventParameter event parameter.
	 */
	function onLoad($param)
	{
		$app = $this->Application->getGlobalization();
		
		$cookiename = $this->ID.'_lang';

		if(isset($_COOKIE[$cookiename]))
		{
			$culture = $_COOKIE[$cookiename];
			if(CultureInfo::validCulture($culture))
				$app->Culture = $culture;
		}
			
		$settings = $this->getSettings();
		$app->Culture = $settings['lang'];		
		
		setcookie($cookiename, $app->Culture, time()+604800);
		
		parent::onLoad($param);
	}
	
	/**
	 * Get a list of available catalogues from the current location.
	 * @param string MessageSource type, e.g. XLIFF
	 * @param string the MesageSource source string.
	 * @param array list of available catalouges.
	 */
	function getCatalogues($type, $location) 
	{
		$source = MessageSource::factory($type, $location);	
		$catalogues = $source->catalogues();
		for($i = 0; $i < count($catalogues); $i++)
			$catalogues[$i][0] .= '.'.$catalogues[$i][1];
			
		return $catalogues;					
	}

	/**
	 * Get the settings from the URL parameters.
	 * @param array list of parameters from the URL
	 */
	function getSettings() 
	{
		$app = $this->Application->getGlobalization();
		$type = $app->Translation['type'];
		$source = $app->Translation['source'];
		$lang = $app->Culture;
		
		if(isset($_GET['source']))
			$params['source'] = rawurldecode($_GET['source']);
		else
			$params['source'] = $source;

		if(isset($_GET['type']))
			$params['type'] = $_GET['type'];
		else
			$params['type'] = $type;
	
		if(isset($_GET['catalogue']))
			$params['catalogue'] = $_GET['catalogue'];
		else
			$params['catalogue'] = 'messages';			

		if(isset($_GET['culture']))
			$params['culture'] = $_GET['culture'];
		else
			$params['culture'] = '';			
			
		if(isset($_GET['lang']))
			$params['lang'] = $_GET['lang'];
		else
			$params['lang'] = $lang;
		
		return $params;
	}
	
	/**
	 * Construct a list of parameters for the Dialog and MessageList URLs.
	 * This is to be used in Editor.php
	 * @param string the source string
	 * @param string the source type.
	 * @param string the catalogue
	 * @param string the currency language.
	 * @return array list of parameters.
	 * @see Editor::getURL();
	 */
	function setSettings($source, $type, $cat, $lang)
	{
		$params['source'] = rawurlencode($source);
		$params['type'] = $type;
		if(count($cat)>1)
		{
			$params['catalogue'] = $cat[0];
			$params['culture'] = $cat[1];
		}
		$params['lang'] = $lang;
		return $params;
	}
	
	/**
	 * Update a message translation.
	 * @param string the message id/source
	 * @param string the translated message
	 * @param string comments
	 * @param array translation settings.
	 * @return true if translation was updated, false otherwise. 
	 */
	function updateMessage($text, $target, $comments, $settings)
	{
		$source = MessageSource::factory($settings['type'], 
										 $settings['source']);	
		$app = $this->Application->getGlobalization();
		
		if($app->Cache)
				$source->setCache(new MessageCache($app->Cache));
		
		$source->setCulture($settings['culture']);
				
		return $source->update($text, $target, 
								$comments, $settings['catalogue']);
	}
}

?>