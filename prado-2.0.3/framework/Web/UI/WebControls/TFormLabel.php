<?php
/**
 * TFormLabel class file
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Qiang Xue. All rights reserved.
 * Copyright(c) 2004 by Xiang Wei Zhuo. 
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Xiang Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.9 $  $Date: 2005/03/11 21:31:18 $
 * @package System.Web.UI.WebControls
 */

/**
 * TFormLabel class
 *
 * TFormLabel displays a label for form input on a Web page. You can
 * customize the label text through the <b>Text</b> property. The
 * corresponding input form element for the label is specified by
 * the <b>For</b> property. If <b>Text</b> is empty, the body
 * content enclosed by the TFormLabel component will be rendered.
 *
 * Example:
 * <code>
 * <com:TFormLabel For="Username" Text="Username:" />
 * <com:TTextbox ID="Username" />
 * </code>
 *
 * Note, <b>Text</b> will be HTML encoded before it is displayed in the TFormLabel component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text of TFormLabel component.
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether Text should be HTML-encoded when rendering.
 * - <b>For</b>, string, kept in viewstate
 *   <br>Gets or sets the for ID of the TFormLabel
 *
 * @author Xiang Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.9 $  $Date: 2005/03/11 21:31:18 $
 * @package System.Web.UI.WebControls
 */
class TFormLabel extends TWebControl
{

	/**
	 * Constructor.
	 * Sets TagName property to 'label'.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('label');
	}

	/**
	 * @return string the FOR ID of the form label
	 */
	public function getForID()
	{
		return $this->getViewState('ForID','');
	}

	/**
	 * Sets the FOR attribute of the form label.
	 * @param string the ID of an form input element to be set
	 */
	public function setForID($value)
	{
		$this->setViewState('ForID',$value,'');
	}

	/**
	 * @return string the text caption of the form label
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text content of the form label.
	 * @param string the text label to be set
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
	 * This overrides the parent implementation by rendering the FOR
     * attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attr=parent::getAttributesToRender();
		$forID=$this->getForID();

		$parent=$this->getParent();
		$for=$parent->findObject($forID);

		if(is_null($for))
			throw new Exception("Invalid \"For\" attribute in TFormLabel value: $forID.");
		else 
		{
			if($for instanceof TListControl) 
			{
				if(isset($attr['onclick']))
					$onclick = explode(';', $attr['onclick']);
				$onclick[] = 'document.getElementById(\''.$for->getClientID().'\').focus();return false;';
				$attr['onclick'] = implode(';', $onclick);
			} 

			$attr['for']=$for->getClientID();
		}

		return $attr;
   } 
	/**
	 * Renders <b>Text</b> or children elements as content.
	 * This method overrides parent's implementation.
	 */
	protected function renderBody()
	{
		$text=$this->isEncodeText()?pradoEncodeData($this->getText()):$this->getText();
		return strlen($text)?$text:parent::renderBody();
	}
}

?>