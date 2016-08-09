<?php
/**
 * TTableRow class file
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
 * @version $Revision: 1.7 $  $Date: 2005/06/21 07:12:39 $
 * @package System.Web.UI.WebControls
 */

/**
 * TTableCell class file
 */
require_once(dirname(__FILE__).'/TTableCell.php');

/**
 * TTableRow class
 *
 * A TTableRow control represents an HTML table row.
 * TTableRow should be used together with a TTable control.
 * For example of usage, please see the documentation of TTable.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>HorizontalAlign</b>, string (Right, Center, Left, Justify, NotSet), default=NotSet, kept in viewstate
 *   <br>Gets or sets the horizontal alignment of the content in the table row.
 * - <b>VerticalAlign</b>, string (NotSet,Top,Middle,Bottom,Baseline), default=NotSet, kept in viewstate
 *   <br>Gets or sets the vertical alignment of the content in the table row.
 * - <b>Cells</b>, TCollection, read-only
 *   <br>Gets the list of table cells in the row.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TTableRow extends TWebControl
{
	/**
	 * Constructor.
	 * Initializes the tagname to 'tr'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('tr');
	}

	/**
	 * Determines whether the control can add the object as a body.
	 * Only TTableCell or its descendant can be added as body.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return ($object instanceof TTableCell);
	}

	/**
	 * @return array list of TTableCell components
	 */
	public function getCells()
	{
		return $this->getBodies();
	}

	/**
	 * @return string the horizontal alignment of the table row
	 */
	public function getHorizontalAlign()
	{
		return $this->getViewState('HorizontalAlign','NotSet');
	}

	/**
	 * Sets the horizontal alignment of the table row
	 * @param string Acceptable values include Left, Justify, Center, Right, NotSet
	 */
	public function setHorizontalAlign($value)
	{
		if($value!=='Right' && $value!=='Center' && $value!=='Left' && $value!=='Justify')
			$value='NotSet';
		$this->setViewState('HorizontalAlign',$value,'NotSet');
	}

	/**
	 * @return string the vertical alignment of the table row
	 */
	public function getVerticalAlign()
	{
		return $this->getViewState('VerticalAlign','NotSet');
	}

	/**
	 * Sets the vertical alignment of the table row
	 * @param string Acceptable values include Top, Bottom, Middle, Baseline, NotSet
	 */
	public function setVerticalAlign($value)
	{
		if($value!=='Top' && $value!=='Bottom' && $value!=='Middle' && $value!=="Baseline")
			$value='NotSet';
		$this->setViewState('VerticalAlign',$value,'NotSet');
	}

	/**
	 * Returns the attributes to be rendered.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
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
	 * Renders the body content of this row.
	 * @return string the rendering result
	 */
	protected function renderBody()
	{
		return "\n".parent::renderBody()."\n";
	}
}

?>