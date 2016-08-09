<?php
/**
 * TBoundColumn class file
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
 * @version $Revision: 1.6 $  $Date: 2005/01/24 02:33:12 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridColumn class file
 */
require_once(dirname(__FILE__).'/TDataGridColumn.php');

/**
 * TBoundColumn class
 *
 * TBoundColumn represents a column that is bound to a field in a data source.
 * The cells in the column will be displayed using the data indexed by
 * <b>DataField</b>. You can customize the display by setting <b>DataFormatString</b>.
 * 
 * If <b>ReadOnly</b> is false, TBoundColumn will display cells in edit mode
 * with textboxes. Otherwise, a static text is displayed.
 *
 * Properties
 * - <b>DataField</b>, string, kept in viewstate
 *   <br>Gets or sets the name of the data field associated with this column.
 * - <b>DataFormatString</b>, string, kept in viewstate
 *   <br>Gets or sets the string that is used to format the DataField value for display. 
 *   The format string is used as the first argument to the sprintf() function.
 * - <b>ReadOnly</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets the value indicating whether the data in this column is read-only.
 * 
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TBoundColumn extends TDataGridColumn
{
	/**
	 * @return string the field name from the data source to bind to the column
	 */
	public function getDataField()
	{
		return $this->getViewState('DataField','');
	}

	/**
	 * @param string the field name from the data source to bind to the column
	 */
	public function setDataField($value)
	{
		$this->setViewState('DataField',$value,'');
	}

	/**
	 * @return string the formatting string used to control how the bound data will be displayed.
	 */
	public function getDataFormatString()
	{
		return $this->getViewState('DataFormatString','');
	}

	/**
	 * @param string the formatting string used to control how the bound data will be displayed.
	 */
	public function setDataFormatString($value)
	{
		$this->setViewState('DataFormatString',$value,'');
	}

	/**
	 * @return boolean whether the items in the column can be edited
	 */
	public function isReadOnly()
	{
		return $this->getViewState('ReadOnly',false);
	}

	/**
	 * @param boolean whether the items in the column can be edited
	 */
	public function setReadOnly($value)
	{
		$this->setViewState('ReadOnly',$value,false);
	}

	/**
	 * Initializes the specified cell to its initial values.
	 * This method overrides the parent implementation.
	 * It creates a textbox for item in edit mode and the column is not read-only.
	 * Otherwise it displays a static text.
	 * The caption of the button and the static text are retrieved
	 * from the datasource.
	 * @param TTableCell the cell to be initialized.
	 * @param integer the index to the Columns property that the cell resides in.
	 * @param string the type of cell (Header,Footer,Item,AlternatingItem,EditItem,SelectedItem)
	 */
	public function initializeCell($cell,$columnIndex,$itemType)
	{
		parent::initializeCell($cell,$columnIndex,$itemType);
		if($itemType===TDataGridItem::TYPE_ITEM || $itemType===TDataGridItem::TYPE_ALTERNATING_ITEM || $itemType===TDataGridItem::TYPE_SELECTED_ITEM || ($itemType===TDataGridItem::TYPE_EDIT_ITEM && $this->isReadOnly()))
		{
			$text=$cell->getContainer()->Data[$this->getDataField()];
			$cell->setText($this->formatDataValue($text));
		}
		else if($itemType===TDataGridItem::TYPE_EDIT_ITEM)
		{
			$textbox=new TTextBox;
			$cell->addChild($textbox);
			$cell->addBody($textbox);
			$text=$cell->getContainer()->Data[$this->getDataField()];
			$textbox->setText($text);
		}
	}

	/**
	 * Formats the text value according to format string.
	 * This method is invoked when setting the text to a cell.
	 * This method can be overriden.
	 * @param mixed the data associated with the cell
	 * @return string the formatted result
	 */
	protected function formatDataValue($value)
	{
		$fs=$this->getDataFormatString();
		if(strlen($fs))
			return sprintf($fs,$value);
		else if(is_bool($value))
			return $value?'True':'False';
		else
			return $value;
	}
}

?>