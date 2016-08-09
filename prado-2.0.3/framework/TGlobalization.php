<?php
/**
 * TGlobalization class file.
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
 * @author Wei Zhuo <weizhuo [at] gmail [dot] com>
 * @version $Revision: 1.10 $  $Date: 2005/08/04 05:27:19 $
 * @package System
 */

/**
 * Get the static translator.
 */
require_once(dirname(__FILE__).'/I18N/Translation.php');

/**
 * TGlobalization class
 *
 * By default, TGlobalization is used by the PRADO framework
 * to store the Culture/Locale, Charset, Translation Configuration, 
 * and Translation Cache information.
 * You should extend to specify how the culture/locale is set. 
 *
 * You can specify your own globalization class in the application specification.
 *
 * Namespace: System
 *
 * @author Wei Zhuo <weizhuo [at] gmail [dot] com>
 * @version v1.0, last update on 24 Dec 2004 
 * @package System
 */
class TGlobalization
{
	/**
	 * Character set, default 'UTF-8'.
	 * @var string 
	 */
	protected $defaultCharset='UTF-8';
	
	/**
	 * Default culture, 'en'.
	 * @var string	 
	 */
	protected $defaultCulture = 'en';
	
	/**
	 * The current culture.
	 * @var string 
	 */
	public $Culture;
	
	/**
	 * The current charset.
	 * @var string 
	 */
	public $Charset;
	
	/**
	 * The current Translation configuration.
	 * @var array
	 */
	public $Translation;
	
	/**
	 * The caching directory for translations.
	 * @var string
	 */
	public $Cache;

	/**
	 * The content type for the http header
	 * @var string
	 */
	public $ContentType = 'text/html';
				
	/**
	 * Constructor.
	 * Create a new Globalization handler. 
	 * @param mixed configuration 
	 */
	public function __construct($config)
	{
		if(isset($config['defaultCharset']))
			$this->defaultCharset = (string)$config['defaultCharset'];
		if(isset($config['defaultCulture']))
			$this->defaultCulture = (string)$config['defaultCulture'];
		
		if($config->translation)
		{
			if(isset($config->translation['type']))
				$this->Translation['type'] = (string)$config->translation['type'];
			if(isset($config->translation['autosave']))
				$this->Translation['autosave'] = (string)$config->translation['autosave'];
			if(isset($config->translation['source']))
				$this->Translation['source'] = (string)$config->translation['source'];	
			if(isset($config->translation['filename']))
				$this->Translation['filename'] = 
						$config->translation['filename'];
			else
				$this->Translation['filename'] = '';						
		}
		
		if($config->cache)
		{
			if(isset($config->cache['dir']))
				$this->Cache = (string)$config->cache['dir'];
		}

		$this->init();
	}

	/**
	 * Initialize the Culture and Charset for this application.
	 * You should override this method if you want a different way of
	 * setting the Culture and/or Charset for your application.
	 * See the I18N example, HTTPGlobalization class for a simple example.
	 * <b>N.B</b>When override this method, be sure to set BOTH
	 * the Culture and Charset.
	 */
	public function init()
	{
		$this->Charset = $this->getDefaultCharset();				
		$this->Culture = $this->getDefaultCulture();
	}
	
	/**
	 * Returns the default culture.
	 * @return string default culture.
	 */
	public function getDefaultCulture()
	{
		return $this->defaultCulture;
	}
	
	/**
	 * Send the content type header.
	 * This method should be called before sending out the content.
	 * Content type header is sent in TPage::execute, before echoing
	 * the contents. Content type and charset can be set with
	 * $this->ContentType and $this->Charset respectively.
	 * @see TPage::execute()
	 */
	public function sendContentTypeHeader()
	{
		header('Content-Type: ' . $this->ContentType . '; charset='.$this->Charset);
	}
	
	/**
	 * Returns the default charset.
	 * @return string the default charset
	 */
	public function getDefaultCharset()
	{
		return $this->defaultCharset;
	}

	/**
	 * Get the variant of a specific culture. If the parameter
	 * $culture is null, the current culture is used.
	 * @param string $culture the Culture string
	 * @return array variants of the culture.
	 */
	public function getVariants($culture=null)
	{
		if(is_null($culture))
			$culture = $this->Culture;

		$variants = explode('_',$culture);

		$variant = null;

		$result = array();
				
		for($i = 0; $i < count($variants); $i++)
		{						
			if(strlen($variants[$i])>0)
			{
				$variant .= ($variant)?'_'.$variants[$i]:$variants[$i];
				$result[] = $variant;
			}
		}
		return array_reverse($result);	

	}
}

?>