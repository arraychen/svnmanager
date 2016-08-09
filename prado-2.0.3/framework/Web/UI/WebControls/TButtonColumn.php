<?php
/**
 * TButtonColumn class file
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
 * @version $Revision: 1.2 $  $Date: 2005/01/24 02:33:13 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridColumn class file
 */
require_once(dirname(__FILE__).'/TDataGridColumn.php');

/**
 * TButtonColumn class
 *
 * TButtonColumn contains a user-defined command button, such as Add or Remove,
 * that corresponds with each row in the column.
 *
 * The caption of the buttons in the column is determined by <b>Text</b>
 * and <b>DataTextField</b> properties. If both are present, the latter takes
 * precedence. The <b>DataTextField</b> refers to the name of the field in datasource
 * whose value will be used as the button caption. If <b>DataTextFormatString</b>
 * is not empty, the value will be formatted before rendering.
 *
 * The buttons in the column can be set to display as hyperlinks or push buttons
 * by setting the <b>ButtonType</b> property.
 * The <b>CommandName</b> will assign its value to all button's <b>CommandName</b>
 * property. The datagrid will capture the command event where you can write event handlers
 * based on different command names.
 *
 * Note, the command buttons created in the column will not cause validation.
 * To enable validation, please use TTemplateColumn instead.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text of the button
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether the button text should be HTML-encoded when rendering.
 * - <b>DataTextField</b>, string, kept in viewstate
 *   <br>Gets or sets the name of the data field associated with the text of the button
 * - <b>DataTextFormatString</b>, string, kept in viewstate
 *   <br>Gets or sets the string that is used to format the DataTextField value for the button text.
 *   The format string is used as the first argument to the sprintf() function.
 * - <b>ButtonType</b>, string (LinkButton,PushButton), default=LinkButton, kept in viewstate
 *   <br>Gets or sets the type of button to be displayed.
 * - <b>CommandName</b>, string, kept in viewstate
 *   <br>Gets or sets the command name associated with command button.
 *
 * Events
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TButtonColumn extends TDataGridColumn
{
	/**
	 * @return string the text caption of the button
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text caption of the button.
	 * @param string the text caption to be set
	 */
	public function setText($value)
	{
		$this->setViewState('Text',$value,'');
	}

	/**
	 * @return boolean whether the text should be HTML encoded before rendering
	 */
	public function isEncodeText()
	{
		return $this->getViewState('EncodeText',true);
	}

	/**
	 * Sets the value indicating whether the text should be HTML encoded before rendering
	 * @param boolean whether the text should be HTML encoded before rendering
	 */
	public function setEncodeText($value)
	{
		$this->setViewState('EncodeText',$value,true);
	}

	/**
	 * @return string the field name from the data source to bind to the button caption
	 */
	public function getDataTextField()
	{
		return $this->getViewState('DataTextField','');
	}

	/**
	 * @param string the field name from the data source to bind to the button caption
	 */
	public function setDataTextField($value)
	{
		$this->setViewState('DataTextField',$value,'');
	}

	/**
	 * @return string the formatting string used to control how the button caption will be displayed.
	 */
	public function getDataTextFormatString()
	{
		return $this->getViewState('DataTextFormatString','');
	}

	/**
	 * @param string the formatting string used to control how the button caption will be displayed.
	 */
	public function setDataTextFormatString($value)
	{
		$this->setViewState('DataTextFormatString',$value,'');
	}

	/**
	 * @return string the type of command button, LinkButton or PushButton
	 */
	public function getButtonType()
	{
		return $this->getViewState('ButtonType','LinkButton');
	}

	/**
	 * @param string the type of command button, LinkButton or PushButton
	 */
	public function setButtonType($value)
	{
		if($value!=='LinkButton' && $value!=='PushButton')
			$value='LinkButton';
		$this->setViewState('ButtonType',$value,'LinkButton');
	}

	/**
	 * @return string the command name associated with the <b>OnCommand</b> event.
	 */
	public function getCommandName()
	{
		return $this->getViewState('CommandName','');
	}

	/**
	 * Sets the command name associated with the <b>OnCommand</b> event.
	 * @param string the text caption to be set
	 */
	public function setCommandName($value)
	{
		$this->setViewState('CommandName',$value,'');
	}

	/**
	 * Initializes the specified cell to its initial values.
	 * This method overrides the parent implementation.
	 * It creates a command button within the cell.
	 * @param TTableCell the cell to be initialized.
	 * @param integer the index to the Columns property that the cell resides in.
	 * @param string the type of cell (Header,Footer,Item,AlternatingItem,EditItem,SelectedItem)
	 */
	public function initializeCell($cell,$columnIndex,$itemType)
	{
		parent::initializeCell($cell,$columnIndex,$itemType);
		if($itemType===TDataGridItem::TYPE_ITEM || $itemType===TDataGridItem::TYPE_ALTERNATING_ITEM || $itemType===TDataGridItem::TYPE_SELECTED_ITEM || $itemType===TDataGridItem::TYPE_EDIT_ITEM)
		{
			$buttonType=$this->getButtonType()==='LinkButton'?'TLinkButton':'TButton';
			$button=$cell->createComponent($buttonType);
			$textField=$this->getDataTextField();
			if(strlen($textField))
			{
				$text=$cell->getContainer()->Data[$textField];
				$text=$this->formatDataTextValue($text);
			}
			else
				$text=$this->getText();
			$button->setText($text);
			$button->setCommandName($this->getCommandName());
			$button->setCausesValidation(false);
			$cell->addBody($button);
		}
	}

	/**
	 * Formats the text value according to format string.
	 * This method is invoked when setting the text to a cell.
	 * This method can be overriden.
	 * @param mixed the data associated with the cell
	 * @return string the formatted result
	 */
	protected function formatDataTextValue($value)
	{
		$fs=$this->getDataTextFormatString();
		return strlen($fs)?sprintf($fs,$value):$value;
	}
}

?>