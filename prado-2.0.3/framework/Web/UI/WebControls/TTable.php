<?php
/**
 * TTable class file
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
 * @version $Revision: 1.9 $  $Date: 2005/01/23 23:01:30 $
 * @package System.Web.UI.WebControls
 */

/**
 * TTableRow class file
 */
require_once(dirname(__FILE__).'/TTableRow.php');

/**
 * TTable class
 *
 * A TTable control represents an HTML table on a page.
 *
 * A TTable maintains a list of TTableRow controls, each of
 * which contains a list of TTableCell controls.
 *
 * There are two ways to construct a table.
 * The first way uses template by the following syntax,
 * <code>
 *   <com:TTable>
 *     <com:TTableRow>
 *       <com:TTableHeaderCell Text="test" />
 *       <com:TTableCell> <com:TTextBox ID="search" /></com:TTableCell>
 *     </com:TTableRow>
 *     <com:TTableRow>
 *       <com:TTableCell Text="foot" />
 *       <com:TTableCell Text="again" />
 *     </com:TTableRow>
 *   <com:TTable>
 * </code>
 * Note, table is the parent of rows which are parents of cells.
 * And cells are parents of the content contained within them.
 *
 * The second way constructs a table in code dynamically, e.g.,
 * <code>
 *   $table=new TTable;
 *   $row=new TTableRow;
 *   $cell=new TTableCell; $cell->Text="test"; $row->Cells->add($cell);
 *   $cell=new TTableCell; $cell->Text="test1"; $row->Cells->add($cell);
 *   $cell=new TTableCell; $cell->Text="test2"; $row->Cells->add($cell);
 *   $table->Rows->add($row);
 * </code>
 *
 * Note, TTable will not keep viewstate of its rows and cells.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>HorizontalAlign</b>, string (Right, Center, Left, Justify, NotSet), default=NotSet, kept in viewstate
 *   <br>Gets or sets the horizontal alignment of the table on page.
 * - <b>CellPadding</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the cellpadding for the table
 * - <b>CellSpacing</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the amount of space between table cells.
 * - <b>GridLines</b>, string (Both, Vertical, Horizontal, None), default=Both, kept in viewstate
 *   <br>Gets or sets a value specifying whether the border between the cells is displayed.
 * - <b>Rows</b>, TCollection, read-only
 *   <br>Gets the list of rows in the table.
 * - <b>BackImageUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the URL of the background image to display behind the table control.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TTable extends TWebControl
{
	/**
	 * Constructor.
	 * Initializes the tagname to 'table'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('table');
	}

	/**
	 * Determines whether the control can add the object as a body.
	 * Only TTableRow or its descendant can be added as body.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return ($object instanceof TTableRow);
	}

	/**
	 * @return array list of TTableRow components
	 */
	public function getRows()
	{
		return $this->getBodies();
	}

	/**
	 * @return string the horizontal alignment of the table on the page.
	 */
	public function getHorizontalAlign()
	{
		return $this->getViewState('HorizontalAlign','NotSet');
	}

	/**
	 * Sets the horizontal alignment of the table on the page
	 * @param string Acceptable values include Left, Justify, Center, Right, NotSet
	 */
	public function setHorizontalAlign($value)
	{
		if($value!=='Right' && $value!=='Center' && $value!=='Left' && $value!=='Justify')
			$value='NotSet';
		$this->setViewState('HorizontalAlign',$value,'NotSet');
	}

	/**
	 * @return string the value specifying whether the border between the cells is displayed.
	 */
	public function getGridLines()
	{
		return $this->getViewState('GridLines','Both');
	}

	/**
	 * Sets the value specifying whether the border between the cells is displayed.
	 * @param string Acceptable values include None, Horizontal, Vertical, Both
	 */
	public function setGridLines($value)
	{
		if($value!=='None' && $value!=='Horizontal' && $value!=='Vertical')
			$value='Both';
		$this->setViewState('GridLines',$value,'Both');
	}
	
	/**
	 * @return integer the cellspacing for the table, -1 if not set.
	 */
	public function getCellSpacing()
	{
		return $this->getViewState('CellSpacing', -1);
	}

	/**
	 * Sets the cellspacing for the table.
	 * @param integer the cellspacing for the table, -1 if not set.
	 */
	public function setCellSpacing($value)
	{
		$this->setViewState('CellSpacing', $value, -1);
	}

	/**
	 * @return integer the cellpadding for the table, -1 if not set.
	 */
	public function getCellPadding()
	{
		return $this->getViewState('CellPadding', -1);
	}
	
	/**
	 * Sets the cellpadding for the table.
	 * @param integer the cellpadding for the table, -1 if not set.
	 */
	public function setCellPadding($value)
	{
		$this->setViewState('CellPadding', $value, -1);
	}

	/**
	 * @return string the background image url for the table
	 */
	public function getBackImageUrl()
	{
		return $this->getViewState('BackImageUrl',false);
	}

	/**
	 * @param boolean the background image url for the table
	 */
	public function setBackImageUrl($value)
	{
		$this->setViewState('BackImageUrl',$value,false);
	}

	/**
	 * Returns the attributes to be rendered.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$url=$this->getBackImageUrl();
		if(strlen($url))
			$this->setStyle(array('background-image'=>"url($url)"));
		$attributes=parent::getAttributesToRender();
		if(($cellSpacing=$this->getCellSpacing())>=0)
			$attributes['cellspacing']=$cellSpacing;
		else
			$attributes['cellspacing']='0';
		if(($cellPadding=$this->getCellPadding())>=0)
			$attributes['cellpadding']=$cellPadding;
		else
			$attributes['cellpadding']='0';
		$grid=$this->getGridLines();
		if($grid!='None')
		{
			if($grid=='Horizontal')
				$attributes['rules']='rows';
			else if($grid=='Both')
				$attributes['rules']='all';
			else if($grid=='Vertical')
				$attributes['rules']='cols';
			if(!isset($attributes['border']))
				$attributes['border']='1';
		}
		$align=$this->getHorizontalAlign();
		if($align!='NotSet')
			$attributes['align']=$align;
		return $attributes;
	}

	/**
	 * Renders the body content of this table.
	 * @return string the rendering result
	 */
	protected function renderBody()
	{
		return "\n".parent::renderBody()."\n";
	}
}

?>