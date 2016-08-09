<?php
/**
 * TTemplateColumn class file
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
 * @version $Revision: 1.3 $  $Date: 2005/01/24 02:33:20 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridColumn class file
 */
require_once(dirname(__FILE__).'/TDataGridColumn.php');

/**
 * TTemplateColumn class
 *
 * TTemplateColumn customizes the layout of controls in the column with templates.
 * In particular, you can specify <b>ItemTemplate</b>, <b>EditItemTemplate</b>
 * <b>HeaderTemplate</b> and <b>FooterTemplate</b> to customize specific
 * type of cells in the column.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>ItemTemplate</b>, string
 *   <br>Gets or sets the template for item cell not in edit mode.
 * - <b>EditItemTemplate</b>, string
 *   <br>Gets or sets the template for edit item cell
 * - <b>HeaderTemplate</b>, string
 *   <br>Gets or sets the template for header cell
 * - <b>FooterTemplate</b>, string
 *   <br>Gets or sets the template for footer cell
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TTemplateColumn extends TDataGridColumn
{
	/**
	 * Various item templates
	 * @var string
	 */
	private $itemTemplate='';
	private $editItemTemplate='';
	private $headerTemplate='';
	private $footerTemplate='';

	/**
	 * @return string the edit item template string
	 */
	public function getEditItemTemplate()
	{
		return $this->editItemTemplate;
	}

	/**
	 * Sets the edit item template string
	 * @param string the edit item template
	 */
	public function setEditItemTemplate($value)
	{
		$this->editItemTemplate=$value;
	}

	/**
	 * @return string the template string for the item
	 */
	public function getItemTemplate()
	{
		return $this->itemTemplate;
	}

	/**
	 * Sets the template string for the item
	 * @param string the item template
	 */
	public function setItemTemplate($value)
	{
		$this->itemTemplate=$value;
	}

	/**
	 * @return string the header template string
	 */
	public function getHeaderTemplate()
	{
		return $this->headerTemplate;
	}

	/**
	 * Sets the header template.
	 * The template will be parsed immediately.
	 * @param string the header template
	 */
	public function setHeaderTemplate($value)
	{
		$this->headerTemplate=$value;
	}

	/**
	 * @return string the footer template string
	 */
	public function getFooterTemplate()
	{
		return $this->footerTemplate;
	}

	/**
	 * Sets the footer template.
	 * The template will be parsed immediately.
	 * @param string the footer template
	 */
	public function setFooterTemplate($value)
	{
		$this->footerTemplate=$value;
	}

	/**
	 * Initializes the specified cell to its initial values.
	 * This method overrides the parent implementation.
	 * It initializes the cell based on different templates 
	 * (ItemTemplate, EditItemTemplate, HeaderTemplate, FooterTemplate).
	 * @param TTableCell the cell to be initialized.
	 * @param integer the index to the Columns property that the cell resides in.
	 * @param string the type of cell (Header,Footer,Item,AlternatingItem,EditItem,SelectedItem)
	 */
	public function initializeCell($cell,$columnIndex,$itemType)
	{
		if($itemType===TDataGridItem::TYPE_EDIT_ITEM)
		{
			parent::initializeCell($cell,$columnIndex,$itemType);
			$template=$this->getEditItemTemplate();
			if(strlen($template))
				$cell->instantiateTemplate($template);
			else
				$cell->addBody('&nbsp;');
		}
		else if($itemType===TDataGridItem::TYPE_HEADER)
		{
			$headerTemplate=$this->getHeaderTemplate();
			if(strlen($headerTemplate))
				$cell->instantiateTemplate($headerTemplate);
			else
				parent::initializeCell($cell,$columnIndex,$itemType);
		}
		else if($itemType===TDataGridItem::TYPE_FOOTER)
		{
			$footerTemplate=$this->getFooterTemplate();
			if(strlen($footerTemplate))
				$cell->instantiateTemplate($footerTemplate);
			else
				parent::initializeCell($cell,$columnIndex,$itemType);
		}
		else
		{
			parent::initializeCell($cell,$columnIndex,$itemType);
			$template=$this->getItemTemplate();
			if(strlen($template))
				$cell->instantiateTemplate($template);
			else
				$cell->addBody('&nbsp;');
		}
	}
}

?>