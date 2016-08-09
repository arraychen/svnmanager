<?php
/**
 * TRadioButton class file
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
 * @version $Revision: 1.8 $  $Date: 2005/06/25 09:36:24 $
 * @package System.Web.UI.WebControls
 */

/**
 * TCheckBox class file
 */
require_once(dirname(__FILE__).'/TCheckBox.php');

/**
 * TRadioButton class
 *
 * TRadioButton creates a radio box on the page.
 *
 * TRadioButton is similar to TCheckBox except that several TRadioButton components can be grouped
 * together by setting the same <b>GroupName</b> property. Only one TRadioButton component can be checked
 * within a group.
 *
 * To determine whether the TRadioButton component is checked, test the <b>Checked</b> property.
 * The <b>OnCheckedChanged</b> event is raised when the state of the TRadioButton component changes
 * between posts to the server. You can provide an event handler for the <b>OnCheckedChanged</b>
 * event to perform a specific task when the state of the TRadioButton component changes
 * between posts to the server.
 *
 * Note, <b>Text</b> will be HTML encoded before it is displayed in the TRadioButton component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>GroupName</b>, string, kept in viewstate
 *   <br>Gets or sets the name of the group that the radio button belongs to.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRadioButton extends TCheckBox
{
	
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('input');
	}

	/**
	 * Loads user input data.
	 * <b>Note</b> only when a radio button is changed from unchecked to checked
	 * will it trigger postdatachanged event (OnCheckedChanged).
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	public function loadPostData($key,&$values)
	{
		$name=$this->getUniqueID();
		$groupName=$this->getGroupName();
		if(strlen($groupName))
		{
			if(isset($values[$groupName]) && $values[$groupName]==$name)
			{
				$checked=$this->isChecked();
				$this->setChecked(true);
				return !$checked;
			}
			else
			{
				$this->setChecked(false);
				return false;
			}
		}
		else
		{
			if(isset($values[$name]))
			{
				$checked=$this->isChecked();
				$this->setChecked(true);
				return !$checked;
			}
			else
				return false;
		}
	}

	/**
	 * @return string the name of the group that the radio button belongs to
	 */
	public function getGroupName()
	{
		return $this->getViewState('GroupName','');
	}

	/**
	 * Sets the name of the group that the radio button belongs to
	 * @param string the group name
	 */
	public function setGroupName($value)
	{
		$this->setViewState('GroupName',$value,'');
	}
	
	
	/**
	 * Returns the attributes to be rendered.
	 * This method overrides the parent's implementation.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes = parent::getAttributesToRender();
		$attributes["type"] = "radio";

		$groupName=$this->getGroupName();
		if(strlen($groupName))
			$attributes["name"] = $groupName;
			
		$attributes["value"] = $this->getUniqueID();
		
		return $attributes;
	}
}

?>