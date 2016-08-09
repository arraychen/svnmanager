<?php
/**
 * TListItem class file
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
 * @version $Revision: 1.6 $  $Date: 2005/01/04 21:32:45 $
 * @package System.Web.UI.WebControls
 */

/**
 * TListItem class
 *
 * A TListItem control represents an item in the TListControl control, such
 * as heading section, footer section, or a data item. The data items 
 * are stored in the <b>Items</b> property of TListControl control.
 * 
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Selected</b>, mixed
 *   <br>Gets or sets whether this item is selected or not.
 * - <b>Text</b>, mixed
 *   <br>Gets or sets the text displayed for this item.
 * - <b>Value</b>, mixed
 *   <br>Gets or sets the value of the item
 *
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.6 $ $Date: 2005/01/04 21:32:45 $
 * @package System.Web.UI.WebControls
 */
class TListItem extends TComponent
{
	/**
	 * whether the item is selected
	 * @var mixed
	 */
	private $selected=false;
	
	/**
	 * value of the text to be displayed for this item
	 * @var mixed
	 */
	private $text='';
	
	/**
	 * The value of the listitem (if different from the text)
	 * @var string
	 */
	private $value=null;
	
	/**
	 * @return mixed the index of the data item
	 */
	public function isSelected()
	{
		return $this->selected;
	}

	/**
	 * Sets whether the item is selected.
	 * @param mixed the data item index
	 */
	public function setSelected($value)
	{
		$this->selected=$value;
	}

	/**
	 * @return mixed the value of the data item
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Sets the value of the data item
	 * @param mixed the value of the data item
	 */
	public function setText($value)
	{
		$this->text=$value;
	}

	/**
	 * @return string the item value 
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Sets the item value
	 * @param string the item value
	 */
	public function setValue($value)
	{
		$this->value=$value;
	}
}

?>