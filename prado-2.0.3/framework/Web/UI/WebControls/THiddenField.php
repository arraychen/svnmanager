<?php
/**
 * THiddenField class file
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
 * @version $Revision: 1.1 $  $Date: 2005/02/05 03:04:33 $
 * @package System.Web.UI.WebControls
 */

/**
 * THiddenField class
 *
 * THiddenField represents a hidden form field.
 * The field data can be accessed via its <b>Value</b> property.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Value</b>, string, kept in viewstate
 *   <br>Gets or sets the value of THiddenField component.
 *
 * Events
 * - <b>OnValueChanged</b> Occurs when the value of the hidden field component is changed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class THiddenField extends TControl implements IPostBackDataHandler
{
	/**
	 * Constructor.
	 * Sets TagName property to 'input'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('input');
	}

	/**
	 * @return string the value of the THiddenField
	 */
	public function getValue()
	{
		return $this->getViewState('Value','');
	}

	/**
	 * Sets the value of the THiddenField
	 * @param string the value to be set
	 */
	public function setValue($value)
	{
		$this->setViewState('Value',$value,'');
	}

	/**
	 * This overrides the parent implementation by rendering more THiddenField-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		$attributes['type']="hidden";
		$attributes['name']=$this->getUniqueID();
		$attributes['value']=strtr($this->getValue(),array('&'=>'&amp;','"'=>'&quot;'));
		return $attributes;
	}

	/**
	 * Loads hidden field data.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	public function loadPostData($key,&$values)
	{
		if(isset($values[$key]))
		{
			$value=$values[$key];
			if($this->getValue()===$value)
				return false;
			else
			{
				$this->setValue($value);
				return true;
			}
		}
		else
			return false;
	}

	/**
	 * Returns the value of the property that needs validation.
	 * @return mixed the property value to be validated
	 */
	public function getValidationPropertyValue()
	{
		return $this->getValue();
	}

	/**
	 * Raises postdata changed event.
	 * This method calls {@link onValueChanged} method.
	 * This method is primarly used by framework developers.
	 */
	public function raisePostDataChangedEvent()
	{
		$this->onValueChanged(new TEventParameter);
	}

	/**
	 * This method is invoked when the value of the <b>Value</b> property changes between posts to the server.
	 * The method raises 'OnValueChanged' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onValueChanged($param)
	{
		$this->raiseEvent('OnValueChanged',$this,$param);
	}
}

?>