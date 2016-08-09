<?php
/**
 * TDropContainer component class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:weizhuo[at]gmail[dot]com Wei Zhuo}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.3 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */

/**
 * TDropContainer class,  a panel to receive TDraggable components.
 * When a TDraggable component is drop into TDropContainer, a Callback request
 * is made to raise OnDrop event on the server.
 *
 * Properties
 * - <b>AcceptCssClass</b>, string, in viewstate
 *   <br />comma delimited classnames of elements that the drop container can accept.
 * - <b>HoverCssClass</b>, string, in viewstate
 *   <br />CSS classname of the container when a draggable element hovers over the container
 *
 * Event
 * - <b>OnDrop</b>, raised when an element is dropped into the container.
 *
 * Namespace: System.Web.UI.ActiveControls
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.3 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */
class TDropContainer extends TActivePanel
{
	public function getAcceptCssClass()
	{
		return $this->getViewState('Accepts', '');
	}

	public function setAcceptCssClass($value)
	{
		$this->setViewState('Accepts', $value, '');
	}

	public function setHoverCssClass($value)
	{
		$this->setViewState('HoverClass', $value, '');
	}

	public function getHoverCssClass()
	{
		return $this->getViewState('HoverClass', '');
	}

	/**
	 * Callback request, raises OnDrop event.
	 */
	public function raiseCallbackEvent($param)
	{
		$id = str_replace(':','.',$param);
		$this->raiseEvent("OnDrop", $this, $id);
		parent::raiseCallbackEvent($param);
	}

	protected function renderClientScript()
	{
		$options = $this->renderClientScriptOptions();
		$script = "new Prado.DropContainer('{$this->ClientID}', $options)";
		$this->Page->registerEndScript($this->ClientID, $script);
	}

	protected function getClientScriptOptions()
	{
		$options = parent::getClientScriptOptions();
		$accepts = preg_split("/\s*,\s* /", $this->getAcceptCssClass());
		$options['accept'] = TJavascript::toArray($accepts);
		$options['hoverclass'] = $this->getHoverCssClass();
		return $options;
	}
}
?>