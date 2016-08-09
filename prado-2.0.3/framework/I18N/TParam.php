<?php

/**
 * TParam component.
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
 * @version $Revision: 1.2 $  $Date: 2005/01/05 03:15:13 $
 * @package System.I18N
 */
 
/**
 * TParam component should be used inside the TTranslate component to
 * allow parameter substitution.
 * 
 * For example, the strings "{greeting}" and "{name}" will be replace
 * with the values of "Hello" and "World", respectively.
 * The substitution string must be enclose with "{" and "}".
 * The parameters can be further translated by using TTranslate.
 * <code>
 * <com:TTranslate>
 *   {greeting} {name}!
 *   <com:TParam Key="name">World</com:TParam>
 *   <com:TParam Key="greeting">Hello</com:TParam>
 * </com:TTranslate>
 * </code>
 *
 * Namespace: System.I18N
 *
 * Properties
 * - <b>Key</b>, string, <b>required</b>.
 *   <br>Gets or sets the string in TTranslate to substitute.
 * - <b>Trim</b>, boolean,
 *   <br>Gets or sets an option to trim the contents of the TParam.
 *   Default is to trim the contents.
 * 
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version v1.0, last update on Fri Dec 24 21:24:05 EST 2004
 * @package System.I18N
 */
class TParam extends TControl
{
	/**
	 * The substitution key.
	 * @var string 
	 */
	protected $key;
	
	/**
	 * To trim or not to trim the contents.
	 * @var boolean 
	 */
	protected $trim = true;
	
	/**
	 * Get the parameter substitution key.
	 * @return string substitution key. 
	 */
	function getKey()
	{
		if(empty($this->key))
			throw new Exception('The Key property must be specified.');
		return $this->key;
	}
	
	/**
	 * Set the parameter substitution key.
	 * @param string substitution key. 
	 */
	function setKey($value)
	{
		$this->key = $value;
	}
	
	/**
	 * Set the option to trim the contents.
	 * @param boolean trim or not.
	 */
	function setTrim($value)
	{
		$this->trim = (boolean)$value;
	}
	
	/**
	 * Trim the content or not.
	 * @return boolean trim or not. 
	 */
	function doTrim()
	{
		return $this->trim;
	}
	
	/**
	 * Get the contents and add the content to the TTranslate
	 * component. This component does not render anything.
	 * The parent container must be of TTranslate, otherwise
	 * error.
	 */
	protected function renderBody()
	{
		$text = parent::renderBody();
		if($this->doTrim())
			$text = trim($text);
		$container = $this->getContainer();
		$container->addParameter($this->getKey(), $text);
	}
	
}

?>