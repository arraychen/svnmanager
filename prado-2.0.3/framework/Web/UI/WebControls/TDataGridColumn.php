<?php
/**
 * TDataGridColumn class file
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
 * @version $Revision: 1.7 $  $Date: 2005/10/09 10:24:12 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDataGridColumn class
 *
 * TDataGridColumn serves as the base class for the different column types of the TDataGrid control.
 * TDataGridColumn defines the properties and methods that are common to all column types.
 * In particular, it initializes header and footer cells according to
 * <b>HeaderText</b>, <b>HeaderStyle</b>, <b>FooterText</b>, and <b>FooterStyle</b>.
 * If <b>HeaderImageUrl</b> is specified, the image will be displayed instead in the header cell.
 * The <b>ItemStyle</b> is applied to non-header and -footer items.
 * 
 * When the datagrid enables sorting, if the <b>SortExpression</b> is not empty,
 * the header cell will display a button (linkbutton or imagebutton) that will
 * bubble sort command event to the datagrid.
 *
 * The framework provides the following TDataGridColumn descendant classes,
 * - TBoundColumn, associated with a specific field in datasource and displays the corresponding data.
 * - TEditCommandColumn, displaying edit/update/cancel command buttons
 * - TButtonColumn, displaying generic command buttons that may be bound to specific field in datasource.
 * - THyperLinkColumn, displaying a hyperlink that may be boudn to specific field in datasource.
 * - TTemplateColumn, displaying content based on templates.
 *
 * To create your own column class, simply override {@link initializeCell()} method,
 * which is the major logic for managing the data and presentation of cells in the column.
 * 
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>ItemStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for item cell
 * - <b>HeaderText</b>, string, stored in viewstate
 *   <br>Gets or sets the text to be displayed in header cell
 * - <b>HeaderStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for header cell
 * - <b>HeaderImageUrl</b>, string, stored in viewstate
 *   <br>Gets or sets the url to the image that will be displayed in header cell
 * - <b>FooterText</b>, string, stored in viewstate
 *   <br>Gets or sets the text to be displayed in footer cell
 * - <b>FooterStyle</b>, string, stored in viewstate
 *   <br>Gets or sets the css style for footer cell
 * - <b>SortExpression</b>, string, stored in viewstate
 *   <br>Gets or sets the sort expression that will be passed to OnSortCommand event parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
abstract class TDataGridColumn extends TControl
{
	/**
	 * @param string text to be displayed in the header of this column
	 */
	public function setHeaderText($value)
	{
		$this->setViewState('HeaderText',$value,'');
	}

	/**
	 * @return string the text to be displayed in the header of this column
	 */
	public function getHeaderText()
	{
		return $this->getViewState('HeaderText','');
	}

	/**
	 * @param string text to be displayed in the footer of this column
	 */
	public function setFooterText($value)
	{
		$this->setViewState('FooterText',$value,'');
	}

	/**
	 * @return string the text to be displayed in the footer of this column
	 */
	public function getFooterText()
	{
		return $this->getViewState('FooterText','');
	}

	/**
	 * @param string style for the header of this column
	 */
	public function setHeaderStyle($value)
	{
		$this->setViewState('HeaderStyle',$value,'');
	}

	/**
	 * @return string the style for the header of this column
	 */
	public function getHeaderStyle()
	{
		return $this->getViewState('HeaderStyle','');
	}

	/**
	 * @param string the url of the image to be displayed in header
	 */
	public function setHeaderImageUrl($value)
	{
		$this->setViewState('HeaderImageUrl',$value,'');
	}

	/**
	 * @return string the url of the image to be displayed in header
	 */
	public function getHeaderImageUrl()
	{
		return $this->getViewState('HeaderImageUrl','');
	}

	/**
	 * @param string style for the footer of this column
	 */
	public function setFooterStyle($value)
	{
		$this->setViewState('FooterStyle',$value,'');
	}

	/**
	 * @return string the style for the footer of this column
	 */
	public function getFooterStyle()
	{
		return $this->getViewState('FooterStyle','');
	}

	/**
	 * @param string style for each item of this column
	 */
	public function setItemStyle($value)
	{
		$this->setViewState('ItemStyle',$value,'');
	}

	/**
	 * @return string the style for each item of this column
	 */
	public function getItemStyle()
	{
		return $this->getViewState('ItemStyle','');
	}

	/**
	 * @param string the name of the field or expression for sorting
	 */
	public function setSortExpression($value)
	{
		$this->setViewState('SortExpression',$value,'');
	}

	/**
	 * @return string the name of the field or expression for sorting
	 */
	public function getSortExpression()
	{
		return $this->getViewState('SortExpression','');
	}

	/**
	 * Initializes the specified cell to its initial values.
	 * The default implementation sets the content of header and footer cells
	 * and the style of other kinds of cell.
	 * If sorting is enabled by the grid and sort expression is specified in the column,
	 * the header cell will show a link/image button. Otherwise, the header/footer cell
	 * will only show static text/image.
	 * This method can be overriden to provide customized intialization to column cells.
	 * @param TTableCell the cell to be initialized.
	 * @param integer the index to the Columns property that the cell resides in.
	 * @param string the type of cell (Header,Footer,Item,AlternatingItem,EditItem,SelectedItem)
	 */
	public function initializeCell($cell,$columnIndex,$itemType)
	{
		if($itemType===TDataGridItem::TYPE_HEADER)
		{
			$grid=$cell->getContainer()->getContainer();
			$sortExpression=$this->getSortExpression();
			$imageUrl=$this->getHeaderImageUrl();
			if($grid->isAllowSorting() && strlen($sortExpression))
			{
				$text=$this->getHeaderText();
				if(strlen($imageUrl))
				{
					$button=$cell->createComponent('TImageButton');
					$button->setImageUrl($imageUrl);
					$button->setAlternateText($text);
					$button->setCommandName(TDataGrid::CMD_SORT);
					$button->setCommandParameter($sortExpression);
					$button->setCausesValidation(false);
					$cell->addBody($button);
				}
				else if(strlen($text))
				{
					$link=$cell->createComponent('TLinkButton');
					$link->setText($text);
					$link->setCommandName(TDataGrid::CMD_SORT);
					$link->setCommandParameter($sortExpression);
					$link->setCausesValidation(false);
					$cell->addBody($link);
				}
				else
					$cell->setText('&nbsp;');
				$style=$this->getHeaderStyle();
				if(strlen($style))
					$cell->setStyle($style);
			}
			else
			{
				$text=$this->getHeaderText();
				if(strlen($imageUrl))
				{
					$image=$cell->createComponent('TImage');
					$image->setImageUrl($imageUrl);
					$image->setAlternateText($text);
					$cell->addBody($image);
				}
				else if(strlen($text))
					$cell->setText($text);
				else
					$cell->setText('&nbsp;');
				$style=$this->getHeaderStyle();
				if(strlen($style))
					$cell->setStyle($style);
			}
		}
		else if($itemType===TDataGridItem::TYPE_FOOTER)
		{
			$text=$this->getFooterText();
			if(!strlen($text))
				$text='&nbsp;';
			$cell->setText($text);
			$style=$this->getFooterStyle();
			if(strlen($style))
				$cell->setStyle($style);
		}
		else
		{
			$itemStyle=$this->getItemStyle();
			if(strlen($itemStyle))
				$cell->setStyle($itemStyle);
		}
		
		//propagate attributes
		$this->propagateAttribute($cell);
	}
	
	/**
	 * Propagate the attributes in the columns to the cells.
	 */
	protected function propagateAttribute($cell)
	{
		foreach($this->getAttributesToRender() as $key => $value)
			$cell->setAttribute($key, $value);
	}
}

?>