<?php
/**
 * TRadioButtonList class file
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
 * @version $Revision: 1.3 $  $Date: 2005/04/29 01:29:47 $
 * @package System.Web.UI.WebControls
 */

/**
 * TCheckBoxList class file
 */
require_once(dirname(__FILE__).'/TCheckBoxList.php');

/**
 * TRadioButtonList class
 *
 * TRadioButtonList creates a radio box on the page.
 *
 * TRadioButtonList is similar to TCheckBoxList except it shows a list of radio buttons and
 * the selection is single.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Example (template)
 * <code>
 *  <com:TRadioButtonList RepeatLayout="Flow" RepeatColumns="2" RepeatDirection="Horizontal">
 *    <com:TListItem Text="item1" Value="value1" />
 *    <com:TListItem Text="item2" Value="value2" />
 *    <com:TListItem Text="item3" Value="value3" />
 *  </com:TRadioButtonList>
 * </code>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRadioButtonList extends TCheckBoxList
{
	/**
	 * This method overrides the parent implementation by returning radio input type.
	 * This method should only be used by component developers.
	 * @return string the input type
	 */
	protected function getInputType()
	{
		return 'radio';
	}
}

?>