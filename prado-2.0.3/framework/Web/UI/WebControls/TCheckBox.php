<?php
/**
 * TCheckBox class file
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
 * @version $Revision: 1.14 $  $Date: 2005/08/08 07:39:52 $
 * @package System.Web.UI.WebControls
 */

/**
 * TCheckBox class
 *
 * TCheckBox creates a check box on the page.
 * You can specify the caption to display beside the check box by setting
 * the <b>Text</b> property.  The caption can appear either on the right
 * or left of the check box, which is determined by the <b>TextAlign</b>
 * property.
 *
 * To determine whether the TCheckBox component is checked,
 * test the <b>Checked</b> property. The <b>OnCheckedChanged</b> event
 * is raised when the <b>Checked</b> state of the TCheckBox component changes
 * between posts to the server. You can provide an event handler for
 * the <b>OnCheckedChanged</b> event to  to programmatically
 * control the actions performed when the state of the TCheckBox component changes
 * between posts to the server.
 *
 * Note, <b>Text</b> will be HTML encoded before it is displayed in the TCheckBox component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text caption displayed in the TCheckBox component.
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether Text should be HTML-encoded when rendering.
 * - <b>TextAlign</b>, Left|Right, default=Right, kept in viewstate
 *   <br>Gets or sets the alignment of the text label associated with the TCheckBox component.
 * - <b>Checked</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets a value indicating whether the TCheckBox component is checked.
 * - <b>AutoPostBack</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets a value indicating whether the TCheckBox automatically posts back to the server when clicked.
 *
 * Events
 * - <b>OnCheckedChanged</b> Occurs when the value of the <b>Checked</b> property changes between posts to the server.
 *
 * Examples
 * - On a page template file, insert the following line to create a TCheckBox component,
 * <code>
 *   <com:TCheckBox Text="Agree" OnCheckedChanged="checkAgree" />
 * </code>
 * The checkbox will show "Agree" text on its right side. If the user makes any change
 * to the <b>Checked</b> state, the checkAgree() method of the page class will be invoked automatically.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TCheckBox extends TWebControl implements IPostBackDataHandler
{
	/**
	 * Constructor.
	 * Sets the tagname to 'span'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('input');
	}

	/**
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return false;
	}

	/**
	 * Loads user input data.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	public function loadPostData($key,&$values)
	{
		$checked=$this->isChecked();
		if(!$this->isEnabled())
		{
		    return false;
		}
		else if(isset($values[$key])!=$checked)
		{
			$this->setChecked(isset($values[$key]));
			return true;
		}
		else
			return false;
	}

	/**
	 * Raises postdata changed event.
	 * This method calls {@link onCheckedChanged} method.
	 * This method is primarly used by framework developers.
	 */
	public function raisePostDataChangedEvent()
	{
		$this->onCheckedChanged(new TEventParameter);
	}

	/**
	 * This method is invoked when the value of the <b>Checked</b> property changes between posts to the server.
	 * The method raises 'OnCheckedChanged' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onCheckedChanged($param)
	{
		$this->raiseEvent('OnCheckedChanged',$this,$param);
	}

	/**
	 * Returns the value of the property that needs validation.
	 * @return mixed the property value to be validated
	 */
	public function getValidationPropertyValue()
	{
		return $this->isChecked();
	}

	/**
	 * @return string the text caption of the checkbox
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text caption of the checkbox.
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
	 * @return boolean whether the checkbox is checked
	 */
	public function isChecked()
	{
		return $this->getViewState('Checked',false);
	}

	/**
	 * Sets a value indicating whether the checkbox is to be checked or not.
	 * @param boolean whether the checkbox is to be checked or not.
	 */
	public function setChecked($value)
	{
		$this->setViewState('Checked',$value,false);
	}

	/**
	 * @return boolean whether clicking on the checkbox will post the page.
	 */
	public function isAutoPostBack()
	{
		return $this->getViewState('AutoPostBack',false);
	}

	/**
	 * Sets a value indicating whether clicking on the checkbox will post the page.
	 * @param boolean whether clicking on the checkbox will post the page.
	 */
	public function setAutoPostBack($value)
	{
		$this->setViewState('AutoPostBack',$value,false);
	}

	/**
	 * Returns the attributes to be rendered.
	 * This method overrides the parent's implementation.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		
		$attributes["id"] = $this->getClientID();
		$attributes["type"] = "checkbox";
		$attributes["name"] = $this->getClientID();

		if($this->isChecked())
			$attributes["checked"] = "checked";
		if(!$this->isEnabled())
			$attributes["disabled"] = "disabled";
		if($this->isAutoPostBack())
		{
			$page=$this->getPage();
			$script=$page->getPostBackClientEvent($this,'');
			$attributes["onclick"] = "javascript:$script";
		}
		$accessKey=$this->getAccessKey();
		if(strlen($accessKey))
			$attributes["acesskey"] = $accessKey;
		$tabIndex=$this->getTabIndex();
		if(!empty($tabIndex))
			$attributes["tabindex"] = $tabIndex;

		return $attributes;
	}

	/**
	 * Renders the body content of the control.
	 * This method overrides the parent's implementation.
	 * @return string the rendering result.
	 */
	public function render()
	{
		$rendered = parent::render();
		$text=$this->isEncodeText()?pradoEncodeData($this->getText()):$this->getText();
		if(strlen($text))
		{
			$name=$this->getUniqueID();
			$label="<label for=\"$name\">$text</label>";
			if($this->getTextAlign()=='Left')
				$rendered = $label.$rendered;
			else
				$rendered = $rendered.$label;
		}
		return $rendered;
	}
}

?>