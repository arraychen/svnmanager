<?php
/**
 * TDropDownList class file
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
 * @version $Revision: 1.24 $  $Date: 2005/02/09 07:12:53 $
 * @package System.Web.UI.WebControls
 */

/**
 * TDropDownList class
 *
 * TDropDownList create a single selection drop-down list on the Web page.
 * This class merely overrides the render() method to output a select
 * list. 
 *
 * Example (template)
 * <code>
 *  <com:TDropDownList>
 *    <com:TListItem Text="item1" Value="value1" />
 *    <com:TListItem Text="item2" Value="value2" Selected="true" />
 *    <com:TListItem Text="item3" Value="value3" />
 *  </com:TDropDownList>
 * </code>
 *
 * @see TListControl
 * 
 * Namespace: System.Web.UI.WebControls
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.24 $  $Date: 2005/02/09 07:12:53 $
 * @package System.Web.UI.WebControls
 */
class TDropDownList extends TListControl // implements IPostBackEventHandler
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('select');
	}
	
	
	/**
	 * Returns the viewstate of this control and its children.
	 * This makes sure there is a selectedItem for a dropdownlist. If 
	 * there is no selected item, the initial one is set as the selected.
	 * @return array|null viewstate to be saved
	 */
	public function saveViewState()
	{
		$index=$this->getSelectedIndex();
		if($index<0 && $this->getItems()->length()>0)
			$this->Items[0]->setSelected(true);
		
		return parent::saveViewState();
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
		$attributes['name']=$this->getUniqueID();
		if($this->isAutoPostBack())
			$attributes['onchange']='javascript:'.$this->getPage()->getPostBackClientEvent($this,'');
		return $attributes;
	}

	/**
	 * Renders the list as an HTML select element.
	 * @return string the rendering result
	 */
	protected function renderBody()
	{
		$content="\n";
		$formatString = $this->getDataTextFormatString();
		foreach($this->getItems() as $item)
		{
			$text=$item->getText();
			if(strlen($formatString))
				$text=sprintf($formatString,$text);
			if($this->isEncodeText())
				$text=pradoEncodeData($text);
			$value=$item->getValue();
			if($item->isSelected())
				$content.='<option value="'.$value.'" selected="selected">'.$text.'</option>'."\n";
			else
				$content.='<option value="'.$value.'">'.$text.'</option>'."\n";
		}
		return $content;
	}
}

?>