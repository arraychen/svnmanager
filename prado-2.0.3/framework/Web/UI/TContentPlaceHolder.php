<?php
/**
 * TContentPlaceHolder class file
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
 * @version $Revision: 1.2 $  $Date: 2005/03/12 02:01:36 $
 * @package System.Web.UI
 */

/**
 * TContentPlaceHolder class
 *
 * TContentPlaceHolder reserves a place in a master page to insert a part of the content page.
 *
 * Each TContentPlaceHolder is associated with a TContent control whose rendering result
 * is inserted at the place where the TContentPlaceHolder is located.
 * The association between the TContentPlaceHolder and the TContent is via matching of their
 * IDs. In case there is no TContent attached to it, TContentPlaceHolder will display its own
 * body content.
 *
 * Namespace: System.Web.UI
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI
 */
class TContentPlaceHolder extends TControl
{
	/**
	 * @var TContent the associated TContent control
	 */
	private $content=null;

	/**
	 * Sets the TContent control.
	 * This method should only be used by framework developers.
	 * @param TContent
	 */
	public function setContent($content)
	{
		$this->content=$content;
	}
	
	/**
	 * Renders the control.
	 * This method overrides parent implementation by rendering 
	 * the associated content control.
	 * @return string the rendering result
	 */
	public function render()
	{
		return is_null($this->content)?parent::render():$this->content->render();
	}
}

?>