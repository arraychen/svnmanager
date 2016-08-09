<?php
/**
 * TImage class file
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
 * @version $Revision: 1.14 $  $Date: 2005/01/23 18:17:37 $
 * @package System.Web.UI.WebControls
 */

/**
 * TImage class
 *
 * TImage displays an image on the page. The path to the displayed image is specified
 * by setting the <b>ImageUrl</b> property, which can be a relative local file path or
 * a URL. You can specify the text to display in place of image when the image is not
 * available by setting the <b>AlternateText</b> property.
 * The alignment of the image in relation to other elements on the Web page is specified
 * by setting <b>ImageAlign</b> property.
 *
 * Note, this control only displays an image. If you need to capture mouse clicks on
 * the image, use the TImageButton or TLinkButton components.
 *
 * Note, <b>AlternateText</b> will be HTML encoded before it is displayed in the TTextBox component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>AlternateText</b>, string, kept in viewstate
 *   <br>Gets or sets the alternate text displayed in the TImage component
 *   when the image is unavailable. Browsers that support the ToolTips feature
 *   display this text as a ToolTip.
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether AlternateText should be HTML-encoded when rendering.
 * - <b>ImageAlign</b>, string, kept in viewstate
 *   <br>Gets or sets the alignment of the TImage component in relation to other elements on the Web page.
 *   Valid values include 'left', 'right', 'baseline', 'top', 'middle', 'bottom', 'absbottom', 'absmiddle', 'texttop'.
 *   TImage will not check the validity of this value.
 * - <b>ImageUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the location of an image to display in the TImage component.
 * - <b>Border</b>, integer, default=0, kept in viewstate
 *   <br>Gets or sets the border width of the TImage component.
 *
 * Examples
 * - On a page template file, insert the following line to create a TImage component,
 * <code>
 *   <com:TImage ImageUrl="images/logo.gif" />
 * </code>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TImage extends TWebControl
{
	/**
	 * Constructor.
	 * Sets TagName property to 'img'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('img');
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
	 * @return string the alternative text displayed in the TImage component when the image is unavailable.
	 */
	public function getAlternateText()
	{
		return $this->getViewState('AlternateText','');
	}

	/**
	 * Sets the alternative text to be displayed in the TImage when the image is unavailable.
	 * @param string the alternative text
	 */
	public function setAlternateText($value)
	{
		$this->setViewState('AlternateText',$value,'');
	}

	/**
	 * @return boolean whether AlternateText should be HTML encoded before rendering
	 */
	public function isEncodeText()
	{
		return $this->getViewState('EncodeText',true);
	}

	/**
	 * Sets the value indicating whether AlternateText should be HTML encoded before rendering
	 * @param boolean whether the text should be HTML encoded before rendering
	 */
	public function setEncodeText($value)
	{
		$this->setViewState('EncodeText',$value,true);
	}

	/**
	 * @return string the alignment of the image with respective to other elements on the page.
	 */
	public function getImageAlign()
	{
		return $this->getViewState('ImageAlign','');
	}

	/**
	 * Sets the alignment of the image with respective to other elements on the page.
	 * @param string the alignment of the image
	 */
	public function setImageAlign($value)
	{
		$this->setViewState('ImageAlign',$value,'');
	}

	/**
	 * @return string the location of the image file to be displayed
	 */
	public function getImageUrl()
	{
		return $this->getViewState('ImageUrl','');
	}

	/**
	 * Sets the location of the image file to be displayed.
	 * @param string the location of the image file (file path or URL)
	 */
	public function setImageUrl($value)
	{
		$this->setViewState('ImageUrl',$value,'');
	}

	/**
	 * @return integer the border width of the image rendered by the TImage component.
	 */
	public function getBorder()
	{
		return $this->getViewState('Border',0);
	}

	/**
	 * Sets the border width of the image rendered by the TImage component.
	 * @param integer the border width
	 */
	public function setBorder($value)
	{
		$this->setViewState('Border',$value,0);
	}

	/**
	 * This overrides the parent implementation by rendering more TImage-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		$attributes['src']=$this->getImageUrl();
		$attributes['border']=$this->getBorder();
		$attributes['alt']=$this->isEncodeText()?pradoEncodeData($this->getAlternateText()):$this->getAlternateText();
		$align=$this->getImageAlign();
		if(strlen($align))
			$attributes['align']=$align;
		return $attributes;
	}
}

?>