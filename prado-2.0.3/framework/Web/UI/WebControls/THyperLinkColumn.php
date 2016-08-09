<?php
/**
 * THyperLinkColumn class file
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
 * @version $Revision: 1.2 $  $Date: 2005/01/24 02:33:20 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridColumn class file
 */
require_once(dirname(__FILE__).'/TDataGridColumn.php');

/**
 * THyperLinkColumn class
 *
 * THyperLinkColumn contains a hyperlink for each item in the column.
 * You can set the text and the url of the hyperlink by <b>Text</b> and <b>NavigateUrl</b>
 * properties, respectively. You can also bind the text and url to specific
 * data field in datasource by setting <b>DataTextField</b> and <b>DataNavigateUrlField</b>.
 * Both can be formatted before rendering according to the <b>DataTextFormatString</b>
 * and <b>DataNavigateUrlFormatString</b> properties, respectively.
 * If both <b>Text</b> and <b>DataTextField</b> are present, the latter takes precedence.
 * The same rule applies to <b>NavigateUrl</b> and <b>DataNavigateUrlField</b> properties.
 * 
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text of the hyperlink
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether the hyperlink text should be HTML-encoded when rendering.
 * - <b>DataTextField</b>, string, kept in viewstate
 *   <br>Gets or sets the name of the data field associated with the text of the hyperlink
 * - <b>DataTextFormatString</b>, string, kept in viewstate
 *   <br>Gets or sets the string that is used to format the DataTextField value for the hyperlink text.
 *   The format string is used as the first argument to the sprintf() function.
 * - <b>NavigateUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the url of the hyperlink
 * - <b>DataNavigateUrlField</b>, string, kept in viewstate
 *   <br>Gets or sets the name of the data field associated with the url of the hyperlink
 * - <b>DataNavigateUrlFormatString</b>, string, kept in viewstate
 *   <br>Gets or sets the string that is used to format the DataNavigateUrlField value for the hyperlink url.
 *   The format string is used as the first argument to the sprintf() function.
 * - <b>Target</b>, string, kept in viewstate
 *   <br>Gets or sets the target window or frame to display the Web page content linked to when the hyperlink component is clicked.
 *   Valid values include '_blank', '_parent', '_self', '_top', and empty string.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class THyperLinkColumn extends TDataGridColumn
{
	/**
	 * @return string the text caption of the hyperlink
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text caption of the hyperlink.
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
	 * @return string the field name from the data source to bind to the hyperlink caption
	 */
	public function getDataTextField()
	{
		return $this->getViewState('DataTextField','');
	}

	/**
	 * @param string the field name from the data source to bind to the hyperlink caption
	 */
	public function setDataTextField($value)
	{
		$this->setViewState('DataTextField',$value,'');
	}

	/**
	 * @return string the formatting string used to control how the hyperlink caption will be displayed.
	 */
	public function getDataTextFormatString()
	{
		return $this->getViewState('DataTextFormatString','');
	}

	/**
	 * @param string the formatting string used to control how the hyperlink caption will be displayed.
	 */
	public function setDataTextFormatString($value)
	{
		$this->setViewState('DataTextFormatString',$value,'');
	}

	/**
	 * @return string the URL to link to when the hyperlink is clicked.
	 */
	public function getNavigateUrl()
	{
		return $this->getViewState('NavigateUrl','');
	}

	/**
	 * Sets the URL to link to when the hyperlink is clicked.
	 * @param string the URL
	 */
	public function setNavigateUrl($value)
	{
		$this->setViewState('NavigateUrl',$value,'');
	}

	/**
	 * @return string the field name from the data source to bind to the navigate url of hyperlink
	 */
	public function getDataNavigateUrlField()
	{
		return $this->getViewState('DataNavigateUrlField','');
	}

	/**
	 * @param string the field name from the data source to bind to the navigate url of hyperlink
	 */
	public function setDataNavigateUrlField($value)
	{
		$this->setViewState('DataNavigateUrlField',$value,'');
	}

	/**
	 * @return string the formatting string used to control how the navigate url of hyperlink will be displayed.
	 */
	public function getDataNavigateUrlFormatString()
	{
		return $this->getViewState('DataNavigateUrlFormatString','');
	}

	/**
	 * @param string the formatting string used to control how the navigate url of hyperlink will be displayed.
	 */
	public function setDataNavigateUrlFormatString($value)
	{
		$this->setViewState('DataNavigateUrlFormatString',$value,'');
	}

	/**
	 * @return string the target window or frame to display the Web page content linked to when the hyperlink is clicked.
	 */
	public function getTarget()
	{
		return $this->getViewState('Target','');
	}

	/**
	 * Sets the target window or frame to display the Web page content linked to when the hyperlink is clicked.
	 * @param string the target window, valid values include '_blank', '_parent', '_self', '_top' and empty string.
	 */
	public function setTarget($value)
	{
		$this->setViewState('Target',$value,'');
	}

	/**
	 * Initializes the specified cell to its initial values.
	 * This method overrides the parent implementation.
	 * It creates a hyperlink within the cell.
	 * @param TTableCell the cell to be initialized.
	 * @param integer the index to the Columns property that the cell resides in.
	 * @param string the type of cell (Header,Footer,Item,AlternatingItem,EditItem,SelectedItem)
	 */
	public function initializeCell($cell,$columnIndex,$itemType)
	{
		parent::initializeCell($cell,$columnIndex,$itemType);
		if($itemType===TDataGridItem::TYPE_ITEM || $itemType===TDataGridItem::TYPE_ALTERNATING_ITEM || $itemType===TDataGridItem::TYPE_SELECTED_ITEM || $itemType===TDataGridItem::TYPE_EDIT_ITEM)
		{
			$textField=$this->getDataTextField();
			if(strlen($textField))
			{
				$text=$cell->getContainer()->Data[$textField];
				$text=$this->formatDataTextValue($text);
			}
			else
				$text=$this->getText();
			$urlField=$this->getDataNavigateUrlField();
			if(strlen($urlField))
			{
				$url=$cell->getContainer()->Data[$urlField];
				$url=$this->formatDataNavigateUrlValue($url);
			}
			else
				$url=$this->getNavigateUrl();
			$link=$cell->createComponent('THyperLink');
			$link->setEncodeText($this->isEncodeText());
			$link->setText($text);
			$link->setNavigateUrl($url);
			$link->setTarget($this->getTarget());
			$cell->addBody($link);
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

	/**
	 * Formats the navigate url value according to format string.
	 * This method is invoked when setting the navigate url to a cell.
	 * This method can be overriden.
	 * @param mixed the data associated with the cell
	 * @return string the formatted result
	 */
	protected function formatDataNavigateUrlValue($value)
	{
		$fs=$this->getDataNavigateUrlFormatString();
		return strlen($fs)?sprintf($fs,$value):$value;
	}
}

?>