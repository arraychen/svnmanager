<?php
/**
 * TActivePanel component class file.
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
 * @version $Revision: 1.1 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */

/**
 * TActivePanel can request to update its or other control's innerHTML via
 * Callback requests.
 *
 * Example: Updating its own innerHTML, will call updateContents on server side
 * <code>
 * <com:TActivePanel ID="Panel1"
 * 		ControlToUpdate="Panel"
 * 		OnUpdate="updateContents" />
 * </code>
 * Javascript uage:
 * <code>
 * 	Prado.ActivePanel.update("Panel1", "a parameter");
 * </code>
 *
 * Properties
 * - <b>ControlToUpate</b>, string, in viewstate
 *   <br />the ID of the element to update its innerHTML upon drop container OnDrop event return
 * - <b>RequestOptions</b>, string, in viewstate
 *   <br />The ID of the TRequestOptions to get the options for AJAX request for OnDrop callback.
 *
 * Events
 * - <b>OnUpdate</b>, raised when Callback is requested.
 *
 * Namespace: System.Web.UI.ActiveControls
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */
class TActivePanel extends TPanel implements ICallbackEventHandler
{

	public function setRequestOptions($value)
	{
		$this->setViewState('RequestOptions', $value, '');
	}

	public function getRequestOptions()
	{
		return $this->getViewState('RequestOptions', '');
	}

	public function getControlToUpdate()
	{
		return $this->getViewState('ControlToUpdate', '');
	}

	public function setControlToUpdate($value)
	{
		$this->setViewState('ControlToUpdate', $value, '');
	}

	/**
	 * Get request options
	 */
	protected function getCallbackOptions()
	{
		$id = $this->getRequestOptions();
		if(strlen(trim($id)) <= 0) return;
		$request = $this->Parent->findObject($id);
		if($request instanceof TRequestOptions )
			return $request->getOptions();
		else
			throw new TException("'{$id}' is not a valid TRequestOptions component");

	}

	public function raiseCallbackEvent($param)
	{
		$this->onCallback($param);
	}

	protected function onCallback($param)
	{
		$this->raiseEvent('OnUpdate', $this, $param);
	}

	/**
	 * Render the javascript within the panel.
	 */
	protected function onPreRender($param)
	{
		parent::onPreRender($param);
		$this->Page->registerClientScript("controls");
		$this->renderClientScript();
	}

	protected function getClientScriptOptions()
	{
		$idPath = $this->getControlToUpdate();
		$control=$this->getParent()->findObject($idPath);
		$options['update'] = is_null($control) ? $idPath: $control->ClientID;
		return $options;
	}

	protected function renderClientScriptOptions()
	{
		$updateOptions = $this->getClientScriptOptions();
		$callbackOptions = $this->getCallbackOptions();
		return TJavascript::toList($updateOptions, $callbackOptions);
	}

	protected function renderClientScript()
	{
		$options = $this->renderClientScriptOptions();
		$script = "Prado.ActivePanel.register('{$this->ClientID}', $options)";
		$this->Page->registerEndScript($this->ClientID, $script);
	}
}

?>