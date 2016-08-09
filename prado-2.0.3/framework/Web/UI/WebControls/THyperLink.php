<?php
/**
 * THyperLink class file
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
 * @version $Revision: 1.8 $  $Date: 2005/04/24 00:21:15 $
 * @package System.Web.UI.WebControls
 */

/**
 * THyperLink class
 *
 * THyperLink displays displays a hyperlink to another Web page.
 *
 * The THyperLink component is typically displayed as text specified by the <b>Text</b> property.
 * If an image is specified by the <b>ImageUrl</b> property, the image will be displayed and the
 * <b>Text</b> is shown as a tooltip of the image.alternative image text.
 * If both <b>Text</b> and <b>ImageUrl</b> are empty, the content enclosed in the body of the
 * THyperLink component will be displayed.
 *
 * Note, <b>Text</b> will be HTML encoded before it is displayed in the THyperLink component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text caption for the THyperLink component.
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether Text should be HTML-encoded when rendering.
 * - <b>NavigateUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the URL to link to when the THyperLink component is clicked.
 * - <b>Target</b>, string, kept in viewstate
 *   <br>Gets or sets the target window or frame to display the Web page content linked to when the THyperLink component is clicked.
 *   Valid values include '_blank', '_parent', '_self', '_top', and empty string.
 * - <b>ImageUrl</b>, string, kept in viewstate
 *   <br>Gets or sets the location of an image to display in the THyperLink component.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class THyperLink extends TWebControl
{
	/**
	 * Constructor.
	 * Sets TagName property to 'a'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('a');
	}

	/**
	 * @return string the text caption of the THyperLink
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text caption of the THyperLink.
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
	 * @return string the location of the image file for the THyperLink
	 */
	public function getImageUrl()
	{
		return $this->getViewState('ImageUrl','');
	}

	/**
	 * Sets the location of image file of the THyperLink.
	 * @param string the image file location
	 */
	public function setImageUrl($value)
	{
		$this->setViewState('ImageUrl',$value,'');
	}

	/**
	 * @return string the URL to link to when the THyperLink component is clicked.
	 */
	public function getNavigateUrl()
	{
		return $this->getViewState('NavigateUrl','');
	}

	/**
	 * Sets the URL to link to when the THyperLink component is clicked.
	 * @param string the URL
	 */
	public function setNavigateUrl($value)
	{
		$this->setViewState('NavigateUrl',$value,'');
	}

	/**
	 * @return string the target window or frame to display the Web page content linked to when the THyperLink component is clicked.
	 */
	public function getTarget()
	{
		return $this->getViewState('Target','');
	}

	/**
	 * Sets the target window or frame to display the Web page content linked to when the THyperLink component is clicked.
	 * @param string the target window, valid values include '_blank', '_parent', '_self', '_top' and empty string.
	 */
	public function setTarget($value)
	{
		$this->setViewState('Target',$value,'');
	}

	/**
	 * This overrides the parent implementation by rendering more THyperLink-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attr=parent::getAttributesToRender();
		$href=$this->getNavigateUrl();
		if(strlen($href) && $this->isEnabled())
			$attr['href']=pradoEncodeData($href);
		$target=$this->getTarget();
		if(strlen($target))
			$attr['target']=$target;
		return $attr;
	}

	protected function renderBody()
	{
		$src=$this->getImageUrl();
		$text=$this->isEncodeText()?pradoEncodeData($this->getText()):$this->getText();
		if(strlen($src))
			return "<img src=\"$src\" border=\"0\" title=\"$text\"/>";
		else if(strlen($text))
			return $text;
		else
			return parent::renderBody();
	}
}

?>