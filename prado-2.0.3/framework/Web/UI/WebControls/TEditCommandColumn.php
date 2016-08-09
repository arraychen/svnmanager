<?php
/**
 * TEditCommandColumn class file
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
 * @version $Revision: 1.4 $  $Date: 2005/04/04 13:46:35 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridColumn class file
 */
require_once(dirname(__FILE__).'/TDataGridColumn.php');

/**
 * TEditCommandColumn class
 *
 * TEditCommandColumn contains the Edit command buttons for editing data items in each row.
 *
 * TEditCommandColumn will create an edit button if a cell is not in edit mode.
 * Otherwise an update button and a cancel button will be created within the cell.
 * The button captions are specified using <b>EditText</b>, <b>UpdateText</b>
 * and <b>CancelText</b>.
 *
 * The buttons in the column can be set to display as hyperlinks or push buttons
 * by setting the <b>ButtonType</b> property.
 *
 * When an edit button is clicked, the datagrid will generate an <b>OnEditCommand</b>
 * event. When an update/cancel button is clicked, the datagrid will generate an
 * <b>OnUpdateCommand</b> or an <b>OnCancelCommand</b>. You can write these event handlers
 * to change the state of specific datagrid item.
 *
 * Properties
 * - <b>EditText</b>, string, kept in viewstate
 *   <br>Gets or sets the caption displayed for edit button.
 * - <b>UpdateText</b>, string, kept in viewstate
 *   <br>Gets or sets the caption displayed for update button.
 * - <b>CancelText</b>, string, kept in viewstate
 *   <br>Gets or sets the caption displayed for cancel button.
 * - <b>ButtonType</b>, string (LinkButton,PushButton), default=LinkButton, kept in viewstate
 *   <br>Gets or sets the type of button to be displayed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TEditCommandColumn extends TDataGridColumn
{
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
	 * @return string the caption of the edit button
	 */
	public function getEditText()
	{
		return $this->getViewState('EditText','');
	}

	/**
	 * @param string the caption of the edit button
	 */
	public function setEditText($value)
	{
		$this->setViewState('EditText',$value,'');
	}

	/**
	 * @return string the caption of the update button
	 */
	public function getUpdateText()
	{
		return $this->getViewState('UpdateText','');
	}

	/**
	 * @param string the caption of the update button
	 */
	public function setUpdateText($value)
	{
		$this->setViewState('UpdateText',$value,'');
	}

	/**
	 * @return string the caption of the cancel button
	 */
	public function getCancelText()
	{
		return $this->getViewState('CancelText','');
	}

	/**
	 * @param string the caption of the cancel button
	 */
	public function setCancelText($value)
	{
		$this->setViewState('CancelText',$value,'');
	}

	/**
	 * Initializes the specified cell to its initial values.
	 * This method overrides the parent implementation.
	 * It creates an update and a cancel button for cell in edit mode.
	 * Otherwise it creates an edit button.
	 * @param TTableCell the cell to be initialized.
	 * @param integer the index to the Columns property that the cell resides in.
	 * @param string the type of cell (Header,Footer,Item,AlternatingItem,EditItem,SelectedItem)
	 */
	public function initializeCell($cell,$columnIndex,$itemType)
	{
		parent::initializeCell($cell,$columnIndex,$itemType);
		$buttonType=$this->getButtonType()=='LinkButton'?'TLinkButton':'TButton';
		if($itemType===TDataGridItem::TYPE_ITEM || $itemType===TDataGridItem::TYPE_ALTERNATING_ITEM || $itemType===TDataGridItem::TYPE_SELECTED_ITEM)
		{
			$editText=$this->getEditText();
			if(strlen($editText))
			{
				$button=$cell->createComponent($buttonType);
				$button->setText($editText);
				$button->setCommandName(TDataGrid::CMD_EDIT);
				$cell->addBody($button);
			}
		}
		else if($itemType===TDataGridItem::TYPE_EDIT_ITEM)
		{
			$updateText=$this->getUpdateText();
			if(strlen($updateText))
			{
				$button=$cell->createComponent($buttonType);
				$button->setText($updateText);
				$button->setCommandName(TDataGrid::CMD_UPDATE);
				$cell->addBody($button);
			}
			$cancelText=$this->getCancelText();
			if(strlen($cancelText))
			{
				$cell->addBody(' ');
				$button=$cell->createComponent($buttonType);
				$button->setText($cancelText);
				$button->setCommandName(TDataGrid::CMD_CANCEL);
				$button->setCausesValidation(false);
				$cell->addBody($button);
			}
		}
	}
}

?>