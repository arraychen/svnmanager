<?php
/**
 * TTextBox class file
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
 * @version $Revision: 1.14 $  $Date: 2005/08/12 15:42:08 $
 * @package System.Web.UI.WebControls
 */

/**
 * TTextBox class
 *
 * TTextBox displays a text box on the Web page for user input.
 * The text displayed in the TTextBox component is specified or determined
 * by using the <b>Text</b> property. You can create a <b>SingleLine</b>,
 * a <b>MultiLine</b>, or a <b>Password</b> text box by setting the <b>TextMode</b> property.
 * If the TTextBox component is a multiline text box, the number of rows
 * it displays is determined by the <b>Rows</b> property, and the <b>Wrap</b> property
 * can be used to determine whether to wrap the text in the component.
 *
 * To specify the display width of the text box, in characters, set the <b>Columns</b> property.
 * To prevent the text displayed in the component from being modified,
 * set the <b>ReadOnly</b> property to true. If you want to limit the user input
 * to a specified number of characters, set the <b>MaxLength</b> property.
 *
 * Note, <b>Text</b> will be HTML encoded before it is displayed in the TTextBox component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text content of the TTextBox component.
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether Text should be HTML-encoded when rendering.
 * - <b>TextMode</b>, string, kept in viewstate
 *   <br>Gets or sets the behavior mode (SingleLine, MultiLine, or Password) of the TTextBox component.
 * - <b>MaxLength</b>, integer, default=0, kept in viewstate
 *   <br>Gets or sets the maximum number of characters allowed in the text box.
 * - <b>Columns</b>, integer, default=0, kept in viewstate
 *   <br>Gets or sets the display width of the text box in characters.
 * - <b>Rows</b>, integer, default=0, kept in viewstate
 *   <br>Gets or sets the number of rows displayed in a multiline text box.
 * - <b>ReadOnly</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets a value indicating whether the contents of the TTextBox component can be changed.
 * - <b>Wrap</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets a value indicating whether the text content wraps within a multiline text box.
 * - <b>AutoTrim</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets a value indicating whether the input text should be trimmed space on both sides
 *   automatically by the framework.
 *   will occur whenever the user modifies the text in the TTextBox component and
 *   then tabs out of the component.
 * - <b>AutoPostBack</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets a value indicating whether an automatic postback to the server
 *   will occur whenever the user modifies the text in the TTextBox component and
 *   then tabs out of the component.
 *
 * Events
 * - <b>OnTextChanged</b> Occurs when the content of the TTextBox component is changed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TTextBox extends TWebControl implements IPostBackDataHandler
{
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
	 * Loads user input data.
	 * This method is primarly used by framework developers.
	 * @param string the key that can be used to retrieve data from the input data collection
	 * @param array the input data collection
	 * @return boolean whether the data of the component has been changed
	 */
	public function loadPostData($key,&$values)
	{
		if(isset($values[$key]))
		{
			$value=$this->isAutoTrim()?trim($values[$key]):$values[$key];
			if($this->getText()===$value)
				return false;
			else
			{
				$this->setText($value);
				return true;
			}
		}
		else
			return false;
	}

	/**
	 * Raises postdata changed event.
	 * This method calls {@link onTextChanged} method.
	 * This method is primarly used by framework developers.
	 */
	public function raisePostDataChangedEvent()
	{
		$this->onTextChanged(new TEventParameter);
	}

	/**
	 * This method is invoked when the value of the <b>Text</b> property changes between posts to the server.
	 * The method raises 'OnTextChanged' event to fire up the event delegates.
	 * If you override this method, be sure to call the parent implementation
	 * so that the event delegates can be invoked.
	 * @param TEventParameter event parameter to be passed to the event handlers
	 */
	public function onTextChanged($param)
	{
		$this->raiseEvent('OnTextChanged',$this,$param);
	}

	/**
	 * Returns the value of the property that needs validation.
	 * @return mixed the property value to be validated
	 */
	public function getValidationPropertyValue()
	{
		return $this->getText();
	}

	/**
	 * @return string the text content of the TTextBox component.
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text content of the TTextBox component.
	 * @param string the text content
	 */
	public function setText($value)
	{
		$this->setViewState('Text',$value,'');
	}

	/**
	 * @return string the behavior mode (SingleLine, MultiLine, or Password) of the TTextBox component.
	 */
	public function getTextMode()
	{
		return $this->getViewState('TextMode','SingleLine');
	}

	/**
	 * Sets the behavior mode (SingleLine, MultiLine, or Password) of the TTextBox component.
	 * @param string the text mode
	 */
	public function setTextMode($value)
	{
		if($value!=='MultiLine' && $value!=='Password')
			$value='SingleLine';
		$this->setViewState('TextMode',$value,'SingleLine');
	}

	/**
	 * @return integer the number of rows displayed in a multiline text box.
	 */
	public function getRows()
	{
		return $this->getViewState('Rows',4);
	}

	/**
	 * Sets the number of rows displayed in a multiline text box.
	 * @param integer the number of rows
	 */
	public function setRows($value)
	{
		$this->setViewState('Rows',$value,4);
	}

	/**
	 * @return integer the display width of the text box in characters.
	 */
	public function getColumns()
	{
		return $this->getViewState('Columns',0);
	}

	/**
	 * Sets the display width of the text box in characters.
	 * @param integer the display width
	 */
	public function setColumns($value)
	{
		$this->setViewState('Columns',$value,0);
	}

	/**
	 * @return integer the maximum number of characters allowed in the text box.
	 */
	public function getMaxLength()
	{
		return $this->getViewState('MaxLength',0);
	}

	/**
	 * Sets the maximum number of characters allowed in the text box.
	 * @param integer the maximum length
	 */
	public function setMaxLength($value)
	{
		$this->setViewState('MaxLength',$value,0);
	}

	/**
	 * @return boolean whether the textbox is read only
	 */
	public function isReadOnly()
	{
		return $this->getViewState('ReadOnly',false);
	}

	/**
	 * @param boolean whether the textbox is read only
	 */
	public function setReadOnly($value)
	{
		$this->setViewState('ReadOnly',$value,false);
	}

	/**
	 * @return boolean whether the text content wraps within a multiline text box.
	 */
	public function isWrap()
	{
		return $this->getViewState('Wrap',true);
	}

	/**
	 * Sets the value indicating whether the text content wraps within a multiline text box.
	 * @param boolean whether the text content wraps within a multiline text box.
	 */
	public function setWrap($value)
	{
		$this->setViewState('Wrap',$value,true);
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
	 * @return boolean a value indicating whether the input text should be trimmed spaces
	 */
	public function isAutoTrim()
	{
		return $this->getViewState('AutoTrim',false);
	}

	/**
	 * Sets the value indicating if the input text should be trimmed spaces
	 * @param boolean the value indicating if the input text should be trimmed spaces
	 */
	public function setAutoTrim($value)
	{
		$this->setViewState('AutoTrim',$value,false);
	}


	/**
	 * @return boolean a value indicating whether an automatic postback to the server
     * will occur whenever the user modifies the text in the TTextBox component and
     * then tabs out of the component.
	 */
	public function isAutoPostBack()
	{
		return $this->getViewState('AutoPostBack',false);
	}

	/**
	 * Sets the value indicating if postback automatically.
	 * An automatic postback to the server will occur whenever the user
	 * modifies the text in the TTextBox component and then tabs out of the component.
	 * @param boolean the value indicating if postback automatically
	 */
	public function setAutoPostBack($value)
	{
		$this->setViewState('AutoPostBack',$value,false);
	}

	/**
	 * Returns the attributes to be rendered.
	 * This method overrides the parent's implementation.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		$attributes['name']=$this->getUniqueID();
		if($this->isReadOnly())
			$attributes['readonly']='readonly';
		if($this->isAutoPostBack())
		{
			$page=$this->getPage();
			$script=$page->getPostBackClientEvent($this,'');
			$attributes['onchange']="javascript:$script";
		}
		$type=$this->getTextMode();
		if($type==='MultiLine')
		{
			if(($rows=$this->getRows())>0)
				$attributes['rows']=$rows;	
			if(($cols=$this->getColumns())>0)
				$attributes['cols']=$cols;
			if(!$this->isWrap())
				$attributes['wrap']='off';
		}
		else
		{
			$attributes['type']=$type==='Password'?'password':'text';
			if(($cols=$this->getColumns())>0)
				$attributes['size']=$cols;
			if(($maxLength=$this->getMaxLength())>0)
				$attributes['maxlength']=$maxLength;
			$attributes['value']=$this->isEncodeText()?pradoEncodeData($this->getText()):$this->getText();
		}
		return $attributes;
	}

	/**
	 * Renders the text box
	 * @return string the rendering result
	 */
	public function render()
	{
		if($this->getTextMode()==='MultiLine')
		{
			$content='<textarea '.$this->renderAttributes().'>';
			$content.=($this->isEncodeText()?pradoEncodeData($this->getText()):$this->getText());
			$content.='</textarea>';
		}
		else
		{
			$content='<input ';
			$content.=$this->renderAttributes();
			$content.=' />';
		}
		return $content;
	}
}

?>