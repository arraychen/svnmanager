<?php
/**
 * TTableCell class file
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
 * @version $Revision: 1.6 $  $Date: 2005/06/21 07:12:39 $
 * @package System.Web.UI.WebControls
 */

/**
 * TTableCell class
 *
 * A TTableCell control represents an HTML table cell.
 * TTableCell should be used together with a TTableRow and TTable control.
 * For example of usage, please see the documentation of TTable.
 *
 * Note, if the <b>Text</b> property is empty, the body content
 * of the cell will be displayed instead.
 *
 * Properties
 * - <b>HorizontalAlign</b>, string (Right, Center, Left, Justify, NotSet), default=NotSet, kept in viewstate
 *   <br>Gets or sets the horizontal alignment of the content in the table cell.
 * - <b>VerticalAlign</b>, string (NotSet,Top,Middle,Bottom,Baseline), default=NotSet, kept in viewstate
 *   <br>Gets or sets the vertical alignment of the content in the table cell.
 * - <b>ColumnSpan</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the column span of the table cell. Value smaller than 0 means the span is not specified.
 * - <b>RowSpan</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the row span of the table cell. Value smaller than 0 means the span is not specified.
 * - <b>Wrap</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets a value indicating whether the text content wraps within the table cell.
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text content to be displayed in the table cell. If the value is empty, the body content will be displayed instead.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TTableCell extends TWebControl
{
	/**
	 * Constructor.
	 * Initializes the tagname to 'td'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('td');
	}

	/**
	 * @return string the horizontal alignment of the table cell
	 */
	public function getHorizontalAlign()
	{
		return $this->getViewState('HorizontalAlign','NotSet');
	}

	/**
	 * Sets the horizontal alignment of the table cell
	 * @param string Acceptable values include Left, Justify, Center, Right, NotSet
	 */
	public function setHorizontalAlign($value)
	{
		if($value!=='Right' && $value!=='Center' && $value!=='Left' && $value!=='Justify')
			$value='NotSet';
		$this->setViewState('HorizontalAlign',$value,'NotSet');
	}

	/**
	 * @return string the vertical alignment of the table cell
	 */
	public function getVerticalAlign()
	{
		return $this->getViewState('VerticalAlign','NotSet');
	}

	/**
	 * Sets the vertical alignment of the table cell
	 * @param string Acceptable values include Top, Bottom, Middle, Baseline, NotSet
	 */
	public function setVerticalAlign($value)
	{
		if($value!=='Top' && $value!=='Bottom' && $value!=='Middle' && $value!=="Baseline")
			$value='NotSet';
		$this->setViewState('VerticalAlign',$value,'NotSet');
	}

	/**
	 * @return integer the columnspan for the table cell, 0 if not set.
	 */
	public function getColumnSpan()
	{
		return $this->getViewState('ColumnSpan', 0);
	}

	/**
	 * Sets the columnspan for the table cell.
	 * @param integer the columnspan for the table cell, 0 if not set.
	 */
	public function setColumnSpan($value)
	{
		$this->setViewState('ColumnSpan', $value, 0);
	}

	/**
	 * @return integer the rowspan for the table cell, 0 if not set.
	 */
	public function getRowSpan()
	{
		return $this->getViewState('RowSpan', 0);
	}

	/**
	 * Sets the rowspan for the table cell.
	 * @param integer the rowspan for the table cell, 0 if not set.
	 */
	public function setRowSpan($value)
	{
		$this->setViewState('RowSpan', $value, 0);
	}

	/**
	 * @return boolean whether the text content wraps within a table cell.
	 */
	public function isWrap()
	{
		return $this->getViewState('Wrap',true);
	}

	/**
	 * Sets the value indicating whether the text content wraps within a table cell.
	 * @param boolean whether the text content wraps within a table cell.
	 */
	public function setWrap($value)
	{
		$this->setViewState('Wrap',$value,true);
	}

	/**
	 * @return string the text content of the table cell.
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text content of the table cell.
	 * @param string the text content
	 */
	public function setText($value)
	{
		$this->setViewState('Text',$value,'');
	}

	/**
	 * Returns the attributes to be rendered.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		if(($colspan=$this->getColumnSpan())>0)
			$attributes['colspan']=$colspan;
		if(($rowspan=$this->getRowSpan())>0)
			$attributes['rowspan']=$rowspan;
		if(!$this->isWrap())
			$attributes['nowrap']='nowrap';
		$align=$this->getHorizontalAlign();
		if($align!='NotSet')
			$attributes['align']=strtolower($align);
		$valign=$this->getVerticalAlign();
		if($valign!='NotSet')
			$attributes['valign']=strtolower($valign);
		unset($attributes['id']);
		return $attributes;
	}

	/**
	 * Renders the body content of this cell.
	 * @return string the rendering result
	 */
	protected function renderBody()
	{
		$text=$this->getText();		
		if($text!=='')
			return $text;
		else
			return parent::renderBody();
	}
}

?>