<?php
/**
 * TLiteral class file
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
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.5 $  $Date: 2005/01/04 21:32:45 $
 * @package System.Web.UI.WebControls
 */

/**
 * TLiteral class
 *
 * TLiteral reserves a location on the Web page to display static text or body content.
 * The TLiteral control is similar to the TLabel control, except the TLiteral
 * control does not allow you to apply a style to the displayed text.
 * You can programmatically control the text displayed in the control by setting
 * the <b>Text</b> property. If the <b>Text</b> property is empty, the content
 * enclosed within the TLiteral control will be displayed. This is very useful
 * for reserving a location on a page because you can add text and controls
 * as children of TLiteral control and they will be rendered at the place.
 *
 * Note, <b>Text</b> is not HTML encoded before it is displayed in the TLiteral component.
 * If the values for the component come from user input, be sure to validate the values
 * to help prevent security vulnerabilities.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text of TLiteral component.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TLiteral extends TControl
{
	/**
	 * @return string the static text of the TLiteral
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the static text of the TLiteral
	 * @param string the text to be set
	 */
	public function setText($value)
	{
		$this->setViewState('Text',$value,'');
	}

	/**
	 * This overrides the parent implementation by rendering the <b>Text</b> property if it is not empty.
	 * @return string the rendering result
	 */
	public function renderBody()
	{
		$text=$this->getText();
		if(strlen($text))
			return $text;
		else
			return parent::renderBody();
	}
}

?>