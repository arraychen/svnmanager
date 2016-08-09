<?php
/**
 * TImageButton class file
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
 * @version $Revision: 1.14 $  $Date: 2005/06/10 04:05:16 $
 * @package System.Web.UI.WebControls
 */

/**
 * TImage class file
 */
require_once(dirname(__FILE__).'/TImage.php');

/**
 * TImageButton class
 *
 * TImageButton displays an image on the Web page and responds to mouse clicks on the image.
 * It is similar to the TButton component except that the TImageButton also captures the
 * coordinates where the image is clicked.
 *
 * Write a <b>OnClick</b> event handler to programmatically determine the coordinates
 * where the image is clicked. The coordinates can be accessed through <b>x</b> and <b>y</b>
 * properties of the event parameter which is of type <b>TImageClickEventParameter</b>.
 * Note the origin (0, 0) is located at the upper left corner of the image.
 *
 * Write a <b>OnCommand</b> event handler to make the TImageButton component behave
 * like a command button. A command name can be associated with the component by using
 * the <b>CommandName</b> property. The <b>CommandParameter</b> property
 * can also be used to pass additional information about the command,
 * such as specifying ascending order. This allows multiple TImageButton components to be placed
 * on the same Web page. In the event handler, you can also determine
 * the <b>CommandName</b> property value and the <b>CommandParameter</b> property value
 * through <b>name</b> and <b>parameter</b> of the event parameter which is of
 * type <b>TCommandEventParameter</b>.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>CausesValidation</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets a value indicating whether validation is performed when the TImageButton component is clicked.
 * - <b>CommandName</b>, string, kept in viewstate
 *   <br>Gets or sets the command name associated with the TLinkButton component that is passed to
 *   the <b>OnCommand</b> event.
 * - <b>CommandParameter</b>, string, kept in viewstate
 *   <br>Gets or sets an optional parameter passed to the <b>OnCommand</b> event along with
 *   the associated <b>CommandName</b>.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */

class TImageButton extends TImage implements IPostBackDataHandler, IPostBackEventHandler
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
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return false;
	}

	/**
	 * @return string the command name associated with the <b>OnCommand</b> event.
	 */
	public function getCommandName()
	{
		return $this->getViewState('CommandName','');
	}

	/**
	 * Sets the command name associated with the <b>OnCommand</b> event.
	 * @param string the text caption to be set
	 */
	public function setCommandName($value)
	{
		$this->setViewState('CommandName',$value,'');
	}

	/**
	 * @return string the parameter associated with the <b>OnCommand</b> event
	 */
	public function getCommandParameter()
	{
		return $this->getViewState('CommandParameter','');
	}

	/**
	 * Sets the parameter associated with the <b>OnCommand</b> event.
	 * @param string the text caption to be set
	 */
	public function setCommandParameter($value)
	{
		$this->setViewState('CommandParameter',$value,'');
	}

	/**
	 * @return boolean whether postback event trigger by this button will cause input validation
	 */
	public function causesValidation()
	{
		return $this->getViewState('CausesValidation',true);
	}

	/**
	 * Sets the value indicating whether postback event trigger by this button will cause input validation.
	 * @param string the text caption to be set
	 */
	public function setCausesValidation($value)
	{
		$this->setViewState('CausesValidation',$value,true);
	}

	/**
	 * Raises postback event.
	 * The implementation of this function should raise appropriate event(s) (e.g. OnClick, OnCommand)
	 * indicating the component is responsible for the postback event.
	 * This method is primarily used by framework developers.
	 * @param string the parameter associated with the postback event
	 */
	public function raisePostBackEvent($param)
	{
		list($x,$y)=explode(',',$param);
		$clickParam=new TImageClickEventParameter;
		$clickParam->x=intval($x);
		$clickParam->y=intval($y);
		$this->onClick($clickParam);
		$cmdParam=new TCommandEventParameter;
		$cmdParam->name=$this->getCommandName();
		$cmdParam->parameter=$this->getCommandParameter();
		$this->onCommand($cmdParam);
	}

	/**
	 * This method is invoked when the component is clicked.
	 * The method raises 'OnClick' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onClick($param)
	{
		$this->raiseEvent('OnClick',$this,$param);
	}

	/**
	 * This method is invoked when the component is clicked.
	 * The method raises 'OnCommand' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TCommandEventParameter event parameter to be passed to the event handlers
	 */
	public function onCommand($param)
	{
		$this->raiseEvent('OnCommand',$this,$param);
		$this->raiseBubbleEvent($this,$param);
	}

	/**
	 * This method checks if the TImageButton is clicked and loads the coordinates of the clicking position.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	public function loadPostData($key,&$values)
	{
		if(isset($values["{$key}_x"]) && isset($values["{$key}_y"]))
		{
			$x=intval($values["{$key}_x"]);
			$y=intval($values["{$key}_y"]);
			$page=$this->getPage();
			$page->setPostBackTarget($key);
			$page->setPostBackParameter("$x,$y");
		}
		return false;
	}

	/**
	 * A dummy implementation for the IPostBackDataHandler interface.
	 */
	public function raisePostDataChangedEvent()
	{
		// no post data to handle
	}

	/**
	 * A dummy implementation for the IPostBackDataHandler interface.
	 * @return null
	 */
	public function getValidationPropertyValue()
	{
		// not data to be validated
		return null;
	}

	/**
	 * This overrides the parent implementation by rendering more TImageButton-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		$attributes['name']=$this->getUniqueID();
		$attributes['type']='image';
		if($this->causesValidation() && $this->Page->isEndScriptRegistered('TValidator'))
		{
			$script = "Prado.Validation.AddTarget('{$this->ClientID}');";
			$this->Page->registerEndScript($this->ClientID.'target', $script);
		}
		return $attributes;
	}
}

/**
 * TImageClickEventParameter class
 *
 * TImageClickEventParameter encapsulates the parameter data for <b>OnClick</b>
 * event of TImageButton components.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TImageClickEventParameter extends TEventParameter
{
	/**
	 * the X coordinate of the clicking point
	 * @var integer
	 */
	public $x=0;
	/**
	 * the Y coordinate of the clicking point
	 * @var integer
	 */
	public $y=0;
}

?>