<?php
/**
 * TCheckBoxList class file
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
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.15 $  $Date: 2005/06/13 07:04:28 $
 * @package System.Web.UI.WebControls
 */

/**
 * TCheckBoxList class
 *
 * TCheckBoxList create a multiple selection listbox on the Web page.
 *
 * This simply overrides the render method to display differently.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>TextAlign</b>, string (Right, Left), default='Right', kept in viewstate
 *   <br>Gets or sets the text alignment with respect to the checkbox
 * - <b>CellPadding</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the cellpadding for the table keeping the checkbox list.
 * - <b>CellSpacing</b>, integer, default=-1, kept in viewstate
 *   <br>Gets or sets the cellspacing for the table keeping the checkbox list.
 * - <b>RepeatColumns</b>, integer, default=1, kept in viewstate
 *   <br>Gets or sets the number of columns that the list should be displayed with.
 * - <b>RepeatDirection</b>, string (Vertical, Horizontal), default='Vertical', kept in viewstate
 *   <br>Gets or sets the direction of traversing the list
 * - <b>RepeatLayout</b>, string (Table, Flow), default=Table, kept in viewstate
 *   <br>Gets or sets how the list should be displayed, using table or using line breaks.
 *
 * Example (template)
 * <code>
 *  <com:TCheckBoxList RepeatLayout="Flow" RepeatColumns="2" RepeatDirection="Horizontal">
 *    <com:TListItem Text="item1" Value="value1" />
 *    <com:TListItem Text="item2" Value="value2" />
 *    <com:TListItem Text="item3" Value="value3" />
 *  </com:TCheckBoxList>
 * </code>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.15 $ $Date: 2005/06/13 07:04:28 $
 * @package System.Web.UI.WebControls
 */
class TCheckBoxList extends TListControl
{
	/**
	 * @return string the alignment of the text caption
	 */
	public function getTextAlign()
	{
		return $this->getViewState('TextAlign','Right');
	}

	/**
	 * Sets the text alignment of the checkbox
	 * @param string either 'Left' or 'Right'
	 */
	public function setTextAlign($value)
	{
		if($value!=='Right' && $value!=='Left')
			$value='Right';
		$this->setViewState('TextAlign',$value,'Right');
	}
	
	/**
	 * @return integer the number of columns that the list should be displayed with.
	 */
	public function getRepeatColumns()
	{
		return $this->getViewState('RepeatColumns', 1);
	}
	
	/**
	 * Sets the number of columns that the list should be displayed with.
	 * @param integer the number of columns that the list should be displayed with.
	 */
	public function setRepeatColumns($value)
	{
		if($value<=0) $value=1;
		$this->setViewState('RepeatColumns', $value,1);
	}
	
	/**
	 * @return string the direction of traversing the list (Vertical, Horizontal)
	 */
	public function getRepeatDirection()
	{
		return $this->getViewState('RepeatDirection', 'Vertical');
	}
	
	/**
	 * Sets the direction of traversing the list (Vertical, Horizontal)
	 * @param string the direction of traversing the list
	 */
	public function setRepeatDirection($value)
	{
		if($value!=='Horizontal')
			$value='Vertical';
		$this->setViewState('RepeatDirection', $value, 'Vertical');
	}
	
	/**
	 * @return string how the list should be displayed, using table or using line breaks (Table, Flow)
	 */
	public function getRepeatLayout()
	{
		return $this->getViewState('RepeatLayout', 'Table');
	}
	
	/**
	 * Sets how the list should be displayed, using table or using line breaks (Table, Flow)
	 * @param string how the list should be displayed, using table or using line breaks (Table, Flow)
	 */
	public function setRepeatLayout($value)
	{
		if($value!=='Flow')
			$value='Table';
		$this->setViewState('RepeatLayout', $value, 'Table');
	}

	/**
	 * @return integer the cellspacing for the table keeping the checkbox list.
	 */
	public function getCellSpacing()
	{
		return $this->getViewState('CellSpacing', -1);
	}

	/**
	 * Sets the cellspacing for the table keeping the checkbox list.
	 * @param integer the cellspacing for the table keeping the checkbox list.
	 */
	public function setCellSpacing($value)
	{
		$this->setViewState('CellSpacing', $value, -1);
	}

	/**
	 * @return integer the cellpadding for the table keeping the checkbox list.
	 */
	public function getCellPadding()
	{
		return $this->getViewState('CellPadding', -1);
	}
	
	/**
	 * Sets the cellpadding for the table keeping the checkbox list.
	 * @param integer the cellpadding for the table keeping the checkbox list.
	 */
	public function setCellPadding($value)
	{
		$this->setViewState('CellPadding', $value, -1);
	}

	/**
	 * Returns the attributes to be rendered.
	 * This method overrides the parent's implementation to default to 
	 * adding a [] to the name
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		if($this->getRepeatLayout()==='Table')
		{
			if(($cellSpacing=$this->getCellSpacing())>=0)
				$attributes['cellspacing']=$cellSpacing;
			else
				$attributes['cellspacing']='0';
			if(($cellPadding=$this->getCellPadding())>=0)
				$attributes['cellpadding']=$cellPadding;
			else
				$attributes['cellpadding']='0';
		}
		//$attributes['id']=$attributes['id'].':cblist';
		return $attributes;
	}

	/**
	 * This method should only be used by component developers.
	 * @return string the input type
	 */
	protected function getInputType()
	{
		return 'checkbox';
	}

	/**
	 * Renders the checkbox list
	 * @return string the rendering result
	 */
	public function render()
	{
		$attr=$this->renderAttributes();
		$id=$this->getUniqueID();
		$items=$this->getItems();
		$count=$items->length();
		$cols=$this->getRepeatColumns();
		$rows=$count%$cols==0?$count/$cols:intval($count/$cols)+1;
		$postback=$this->isAutoPostBack()?'onclick="javascript:'.$this->getPage()->getPostBackClientEvent($this,'').'"':'';
		$lines=array();
		$type=$this->getInputType();
		$formatString = $this->getDataTextFormatString();
		foreach($items as $index=>$item)
		{
			$text=$item->getText();
			$value=$item->getValue();
			if(strlen($formatString))
				$text=sprintf($formatString,$text);
			if($this->isEncodeText())
				$text=pradoEncodeData($text);
			
			if($item->isSelected())
			{
				if ($this->isEnabled()) 
					$input="<input id=\"$id:$index\" name=\"{$id}[]\" value=\"$value\" type=\"$type\" checked=\"checked\" $postback/>";
				else 
					$input="<input id=\"$id:$index\" name=\"{$id}[]\" value=\"$value\" type=\"$type\" disabled=\"disabled\" checked=\"checked\" $postback/>";
			}
			else 
			{
				if ($this->isEnabled()) 
					$input="<input id=\"$id:$index\" name=\"{$id}[]\" value=\"$value\" type=\"$type\" $postback/>";
				else 
					$input="<input id=\"$id:$index\" name=\"{$id}[]\" value=\"$value\" type=\"$type\" disabled=\"disabled\" $postback/>";
			}
			$label=!empty($text) ? "<label for=\"$id:$index\">$text</label>" : "";
			$lines[]=$this->getTextAlign()==='Left'?$label.$input:$input.$label;
		}
		$output=array();
		if($this->getRepeatDirection()==='Vertical')
		{
			$n=0;
			for($j=0;$j<$cols;++$j)
			{
				$r=($count-$n)%($cols-$j)==0?($count-$n)/($cols-$j):intval(($count-$n)/($cols-$j))+1;
				for($i=0;$i<$rows;++$i)
				{
					if($i<$r)
					{
						$output[$i][$j]=$lines[$n];
						$n++;
					}
					else
						$output[$i][$j]='';
				}
			}
		}
		else
		{
			for($i=0;$i<$rows;++$i)
			{
				for($j=0;$j<$cols;++$j)
				{
					$n=$i*$cols+$j;
					if($n<$count)
						$output[$i][$j]=$lines[$n];
					else
						$output[$i][$j]='';
				}
			}
		}
		if($this->getRepeatLayout()==='Table')
		{
			$content="<table $attr>\n";
			for($i=0;$i<$rows;++$i)
			{
				$content.="<tr>\n";
				for($j=0;$j<$cols;++$j)
					$content.="<td>".$output[$i][$j]."</td>\n";
				$content.="</tr>\n";
			}
			$content.="</table>\n";
		}
		else // RepeatLayout=Flow
		{
			$content="<span $attr>";
			for($i=0;$i<$rows;++$i)
			{
				for($j=0;$j<$cols;++$j)
					$content.=$output[$i][$j];
				$content.="<br/>";
			}
			$content.="</span>\n";
		}
		return $content;
	}
}

?>