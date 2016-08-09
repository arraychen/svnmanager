<?php
/**
 * TDraggable class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */

/**
 * TDraggble component class. Client-side draggable container, use in conjuction
 * with TDropContainer.
 *
 * Controls within TDraggble can be dragged on the client browser. When the 
 * Revert property is true, up releasing the TDraggable container will return
 * to its initial position on the page.
 *
 * Properties
 * - <b>Revert</b>, boolean, saved in viewstate, default True.
 *   <br />If true, releasing TDraggable container will return to its original position.
 *
 * Namespace: System.Web.UI.ActiveControls
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */
class TDraggable extends TPanel
{
	public function getRevert()
	{
		return $this->getViewState('revert', true);
	}
	
	public function setRevert($value)
	{
		$this->setViewState('revert', $value, true);
	}
	
	protected function onPreRender($param)
	{
		parent::onPreRender($param);
		$this->Page->registerClientScript("controls");

	}
	protected function renderBody()
	{
		$contents = parent::renderBody();
		$options = TJavascript::toList(array('revert' => $this->getRevert()));
		$script = "new Draggable('{$this->ClientID}', {$options});";
		$contents .= TJavascript::render($script);
		return $contents;
	}
}

?>