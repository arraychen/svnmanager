<?php

/**
 * TTranslate, I18N translation component.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Xiang Wei Zhuo. 
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.11 $  $Date: 2005/10/09 10:24:12 $
 * @package System.I18N
 */

/**
 * TTranslate class.
 * 
 * This component performs message/string translation. The translation
 * source is set in the TGlobalization handler. The following example
 * demonstrated a simple message translation.
 * <code>
 * <com:TTranslate Text="Goodbye" />
 * </code>
 *
 * Depending on the culture set on the page, the phrase "Goodbye" will
 * be translated.
 *
 * The values of any attribute in TTranslate are consider as values for 
 * substitution. Strings enclosed with "{" and "}" are consider as the 
 * parameters. The following example will substitution the string 
 * "{time}" with the value of the attribute "time="#time()". Note that
 * the value of the attribute time is evaluated.
 * <code>
 * <com:TTranslate time="#time()">
 *   The unix-time is "{time}".
 * </com:TTranslate>
 * </code>
 *
 * More complex string substitution can be applied using the
 * TParam component. 
 *
 * Namespace: System.I18N
 *
 * Properties
 * - <b>Text</b>, string, 
 *   <br>Gets or sets the string to translate. 
 * - <b>Catalogue</b>, string,
 *   <br>Gets or sets the catalogue for message translation. The
 *    default catalogue can be set by the @Page directive.
 * - <b>Key</b>, string, 
 *   <br>Gets or sets the key used to message look up.
 * - <b>Trim</b>, boolean,
 *   <br>Gets or sets an option to trim the contents.
 *   Default is to trim the contents.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version v1.0, last update on Fri Dec 24 21:38:49 EST 2004
 * @package System.I18N
 */
class TTranslate extends TI18NControl
{
	
	/**
	 * Adds the object parsed in template as a body of the component.
	 * @param TComponent|string the newly parsed object
	 * @param TComponent the template owner
	 */
	public function addParsedObject($object,$context)
	{		
		if($object instanceof TControl)
		{
			$this->addChild($object);
			$this->addBody($object);
		}
		else
			parent::addParsedObject($object,$context);
	}
		
	/**
	 * @return string the text to be localized/translated.
	 */
	function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text for localization.
	 * @param string the text for translation.
	 */
	function setText($value)
	{
		$this->setViewState('Text',$value,'');
	}

	/**
	 * Set the key for message lookup.
	 * @param string key
	 */
	function setKey($value)
	{
		$this->setViewState('Key',$value,'');
	}
	
	/**
	 * Get the key for message lookup.
	 * @return string key
	 */	
	function getKey()
	{
		return $this->getViewState('Key','');
	}
	
	/**
	 * Get the message catalogue.
	 * @return string catalogue. 
	 */
	function getCatalogue()
	{
		return $this->getViewState('Catalogue','');
	}
	
	/**
	 * Set the message catalogue.
	 * @param string catalogue. 
	 */	
	function setCatalogue($value)
	{
		$this->setViewState('Catalogue',$value,'');
	}
	
	/**
	 * Set the option to trim the contents.
	 * @param boolean trim or not.
	 */	
	function setTrim($value)
	{
		$this->setViewState('Trim',(boolean)$value,true);
	}
	
	/**
	 * Trim the content or not.
	 * @return boolean trim or not. 
	 */	
	function doTrim()
	{
		return $this->getViewState('Trim',true);
	}
		
	/**
	 * Make all the attribute as parameters for subsititution.
	 * The process of getting parameters from the attribute is as follows.
	 *	# Get all the attributes of this component.
	 *	# Stringify all the attributes to 
	 *     ("{$key1}" => "$value", "{$key2}" => localize('hello') ),
	 *    where $key is the attribute name, and $value is the attribute value.
	 *  # Call $this->evaluateExpression($array) to add the string above.
	 * @return array parameters for string replacement.
	 */
	protected function getParameters()
	{
		$strings = array();
		
		$attributes = $this->getViewState('Attributes',true);
		if(!is_array($attributes) || empty($attributes))
			$attributes = $this->getAttributes();
		if(is_array($attributes) && !empty($attributes))
		{
			foreach($attributes as $key=>$value)
			{
				if(strlen($value) > 0 && $value{0}==='#')
					$strings[] = '"{'.$key.'}" => '.$this->quote(substr($value,1));
				else
					$strings[] = '"{'.$key.'}" => "'.$this->quote($value).'"';
			}
		}
		$array = 'array('.implode(',',$strings).')';

		return $this->evaluateExpression($array);
	}	

	protected function quote($string)
	{
		$escaped = addslashes($string);
		return $escaped;
	}
	
	/**
	 * Add a string substitution key value pair.
	 * @param string the string to substitute.
	 * @param string the substitution value.
	 */
	function addParameter($key, $value)
	{
		$this->setAttribute($key, $value);
		$this->setViewState('Attributes', $this->getAttributes(), '');
	}
	
	/**
	 * Display the translated string.
	 */
	protected function renderBody()
	{
		$app = $this->Application->getGlobalization();
		
		//get the text from either Property Text or the body
		$text=$this->getText();
		if(strlen($text) == 0)
			$text = parent::renderBody();
			
		//trim it
		if($this->doTrim())
			$text = trim($text);
			
		//no translation handler provided
		if(empty($app->Translation))
			return strtr($text, $this->getParameters());
			
		Translation::init();
		
		$catalogue = $this->getCatalogue();
		if(empty($catalogue) && isset($app->Translation['catalogue']))
			$catalogue = $app->Translation['catalogue'];
		
		$key = $this->getKey();
		if(!empty($key)) $text = $key;
		
		//translate it
		return Translation::formatter()->format($text,
										$this->getParameters(),
										$catalogue, $this->charset());
	}
	
}

?>