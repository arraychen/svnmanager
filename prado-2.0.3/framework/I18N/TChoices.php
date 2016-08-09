<?php
/**
 * TChoices, I18N choice format component.
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
 * @version $Revision: 1.2 $  $Date: 2005/04/24 00:21:13 $
 * @package System.I18N
 */

 /**
 * Get the ChoiceFormat class.
 */
require_once(dirname(__FILE__).'/core/ChoiceFormat.php');

/**
 * TChoices class.
 * 
 * This component performs message/string choice translation. The translation
 * source is set in the TGlobalization handler. The following example
 * demonstrated a simple 2 choice message translation.
 * <code>
 * <com:TChoices Value="1"/>[1] One Apple. |[2] Two Apples</com:TChoice>
 * </code>
 *
 * The Choice has <b>Value</b> "1" (one), thus the translated string
 * is "One Apple". If the <b>Value</b> was "2", then it will show
 * "Two Apples".
 *
 * The message/string choices are separated by the pipe "|" followed
 * by a set notation of the form
 *  # <t>[1,2]</t> -- accepts values between 1 and 2, inclusive.
 *  # <t>(1,2)</t> -- accepts values between 1 and 2, excluding 1 and 2.
 *  # <t>{1,2,3,4}</t> -- only values defined in the set are accepted.
 *  # <t>[-Inf,0)</t> -- accepts value greater or equal to negative infinity 
 *                       and strictly less than 0
 * Any non-empty combinations of the delimiters of square and round brackets
 * are acceptable.
 * 
 * The string choosen for display depends on the <b>Value</b> property. 
 * The <b>Value</b> is evaluated for each set until the Value is found
 * to belong to a particular set.
 *
 * Namespace: System.I18N
 *
 * Properties
 * - <b>Value</b>, float, 
 *   <br>Gets or sets the Value that determines which string choice to display.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version v1.0, last update on Fri Dec 24 21:38:49 EST 2004
 * @package System.I18N
 */
class TChoices extends TTranslate
{
	/**
	 * @return float the numerical value.
	 */
	function getValue()
	{
		return $this->getViewState('Value','');
	}

	/**
	 * Sets the numerical choice value
	 * @param float the choice value
	 */
	function setValue($value)
	{
		$this->setViewState('Value',$value,'');
	}

	/**
	 * Display the choosen translated string.
	 * Overrides the parent method, also calls parent's renderBody to 
	 * translate.
	 */
	protected function renderBody()
	{
		$choice = new ChoiceFormat();

		//call the parent to translate it first.
		$text = parent::renderBody();

		//trim it
		if($this->doTrim()) $text = trim($text);

		$value = $this->getValue();
		$string = $choice->format($text, $value);
		if($string) 
			return strtr($string, array('{Value}'=> $value));
	}
}
?>