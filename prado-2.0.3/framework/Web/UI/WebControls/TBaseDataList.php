<?php
/**
 * TBaseDataList class file
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
 * @version $Revision: 1.6 $  $Date: 2005/04/01 02:23:43 $
 * @package System.Web.UI.WebControls
 */

/**
 * TBaseDataList class
 *
 * TBaseDataList is the base class for data listing controls including TDataList and TDataGrid.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>HorizontalAlign</b>, string (Right, Center, Left, Justify, NotSet), default=NotSet, kept in viewstate
 *   <br>Gets or sets the horizontal alignment of a data listing control within its container.
 * - <b>CellPadding</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the cellpadding for the table keeping the checkbox list.
 * - <b>CellSpacing</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the amount of space between cells.
 * - <b>GridLines</b>, string (Both, Vertical, Horizontal, None), default=Both, kept in viewstate
 *   <br>Gets or sets a value specifying whether the border between the cells is displayed.
 * - <b>DataSource</b>, Traversable|array
 *   <br>Gets or sets the list of values to be populated the items within the control
 * - <b>DataKeyField</b>, string, kept in viewstate
 *   <br>Gets or sets the key field in the data source specified by the DataSource property.
 * - <b>DataKeys</b>, array, read-only
 *   <br>Gets a list of key values in a data listing control.
 *
 * Events
 * - <b>OnSelectionChanged</b>, Occurs when a different item is selected in a data listing control between postbacks.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Revision: 1.6 $ $Date: 2005/04/01 02:23:43 $
 * @package System.Web.UI.WebControls
 */
abstract class TBaseDataList extends TWebControl
{
	protected $datasource=null;
	/**
	 * @return mixed the data source that populates the items of the data listing control.
	 */
	public function getDataSource()
	{
		return $this->dataSource;
	}

	/**
	 * Sets the data source that populates the items of the data listing control.
	 * @param mixed the data source.
	 */
	public function setDataSource($value)
	{
		if(is_array($value))
			$value=new ArrayObject($value);
		if(is_null($value) || ($value instanceof Traversable))
			$this->dataSource=$value;
		else
			throw new Exception('DataSource must be an array or traversable.');
	}

	/**
	 * @return string the field of the data source that provides the keys of the list items.
	 */
	public function getDataKeyField()
	{
		return $this->getViewState('DataKeyField','');
	}

	/**
	 * @param string the field of the data source that provides the keys of the list items.
	 */
	public function setDataKeyField($value)
	{
		$this->setViewState('DataKeyField',$value,'');
	}
	
	/**
	 * @return array the keys used in the data listing control.
	 */
	public function getDataKeys()
	{
		return array();
	}

	/**
	 * @return string the horizontal alignment of a data listing control within its container.
	 */
	public function getHorizontalAlign()
	{
		return $this->getViewState('HorizontalAlign','NotSet');
	}

	/**
	 * Sets the horizontal alignment of a data listing control within its container.
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
	 * @return integer the cellspacing for the table keeping the data listing items.
	 */
	public function getCellSpacing()
	{
		return $this->getViewState('CellSpacing', -1);
	}

	/**
	 * Sets the cellspacing for the table keeping the data listing items.
	 * @param integer the cellspacing for the table keeping the data listing items.
	 */
	public function setCellSpacing($value)
	{
		$this->setViewState('CellSpacing', $value, -1);
	}

	/**
	 * @return integer the cellpadding for the table keeping the data listing items.
	 */
	public function getCellPadding()
	{
		return $this->getViewState('CellPadding', -1);
	}
	
	/**
	 * Sets the cellpadding for the table keeping the data listing items.
	 * @param integer the cellpadding for the table keeping the data listing items.
	 */
	public function setCellPadding($value)
	{
		$this->setViewState('CellPadding', $value, -1);
	}
}

?>