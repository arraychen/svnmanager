<?php
/**
 * TPanel class file
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
 * @version $Revision: 1.8 $  $Date: 2005/01/15 16:19:42 $
 * @package System.Web.UI.WebControls
 */

/**
 * TPanel class
 *
 * TPanel represents a component that acts as a container for other component.
 * It is especially useful when you want to generate components programmatically or hide/show a group of components.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>BackImageUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the URL of the background image for the panel component.
 * - <b>HorizontalAlign</b>, string, kept in viewstate
 *   <br>Gets or sets the horizontal alignment of the contents within the panel.
 *   Valid values include 'justify', 'left', 'center', 'right'.
 * - <b>Wrap</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets a value indicating whether the content wraps within the panel.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TPanel extends TWebControl
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('div');
	}

	/**
	 * @return boolean whether the content wraps within the panel.
	 */
	public function isWrap()
	{
		return $this->getViewState('Wrap',true);
	}

	/**
	 * Sets the value indicating whether the content wraps within the panel.
	 * @param boolean whether the content wraps within the panel.
	 */
	public function setWrap($value)
	{
		$this->setViewState('Wrap',$value,true);
	}

	/**
	 * @return string the horizontal alignment of the contents within the panel.
	 */
	public function getHorizontalAlign()
	{
		return $this->getViewState('HorizontalAlign','');
	}

	/**
	 * Sets the horizontal alignment of the contents within the panel.
     * Valid values include 'justify', 'left', 'center', 'right' or empty string.
	 * @param string the horizontal alignment
	 */
	public function setHorizontalAlign($value)
	{
		$this->setViewState('HorizontalAlign',$value,'');
	}

	/**
	 * @return string the URL of the background image for the panel component.
	 */
	public function getBackImageUrl()
	{
		return $this->getViewState('BackImageUrl','');
	}

	/**
	 * Sets the URL of the background image for the panel component.
	 * @param string the URL
	 */
	public function setBackImageUrl($value)
	{
		$this->setViewState('BackImageUrl',$value,'');
	}

	/**
	 * This overrides the parent implementation by rendering more TPanel-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$url=$this->getBackImageUrl();
		if(strlen($url))
			$this->setStyle(array('background-image'=>"url($url)"));
		$attributes=parent::getAttributesToRender();
		$align=$this->getHorizontalAlign();
		if(strlen($align))
			$attributes['align']=$align;
		if(!$this->isWrap())
			$attributes['nowrap']='nowrap';
		return $attributes;
	}
}

?>