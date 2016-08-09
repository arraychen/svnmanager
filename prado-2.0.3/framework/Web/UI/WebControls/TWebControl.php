<?php
/**
 * TWebControl class file
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
 * @version $Revision: 1.10 $  $Date: 2005/03/19 12:00:02 $
 * @package System.Web.UI.WebControls
 */

/**
 * TWebControl class
 *
 * TWebControl is the base class for HTML controls that share a set of common
 * properties defining their outlook. These properties are
 * kept in viewstate and can be restored upon a postback request.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Enabled</b>, string, default=true, kept in viewstate
 *   <br>Gets or sets a value indicating whether the control is enabled.
 *   Use the Enabled property to specify or determine whether a control is functional.
 *   When set to false, the control may appear dimmed, preventing any input from being entered.
 * - <b>CssClass</b>, string, kept in viewstate
 *   <br>Gets or sets the Cascading Style Sheet (CSS) class rendered by the control on the client.
 * - <b>AccessKey</b>, string, kept in viewstate
 *   <br>Gets or sets the access key (underlined letter) that allows you to quickly
 *   navigate to the control at the client side. AccessKey must only be assigned a one-character string.
 * - <b>TabIndex</b>, integer, default=0, kept in viewstate
 *   <br>Gets or sets the tab index of the control.
 *   When you press the Tab key, the order in which the controls receive focus
 *   is determined by the TabIndex property of each control. The value 0 means the tab index is not set.
 * - <b>ToolTip</b>, string, kept in viewstate
 *   <br>Gets or sets the text displayed when the mouse pointer hovers over the control.
 * - <b>Style</b>, ArrayObject, kept in viewstate
 *   <br>Gets the Cascading Style Sheet (CSS) rendered by the control on the client.
 *   You can modify a single style in code by treating <b>Style</b> as an array 
 *   and modifying a key and value.
 * - <b>Width</b>, string, default='', kept in viewstate
 *   <br>Gets or sets the width of the control
 * - <b>Height</b>, string, default='', kept in viewstate
 *   <br>Gets or sets the height of the control
 * - <b>ForeColor</b>, string, kept in viewstate
 *   <br>Gets or sets the foreground color of the control
 * - <b>BackColor</b>, string, kept in viewstate
 *   <br>Gets or sets the background color of the control
 * - <b>BorderWidth</b>, string, default='', kept in viewstate
 *   <br>Gets or sets the border width of the control
 * - <b>BorderColor</b>, string, kept in viewstate
 *   <br>Gets or sets the border color of the control
 * - <b>BorderStyle</b>, string, kept in viewstate
 *   <br>Gets or sets the border style of the control
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TWebControl extends TControl
{
	/**
	 * @return boolean whether the control is enabled.
	 */
	public function isEnabled()
	{
		return $this->getViewState('Enabled',true);
	}

	/**
	 * Enables or disables a control.
	 * @param boolean whether the control is to be enabled.
	 */
	public function setEnabled($value)
	{
		$this->setViewState('Enabled',$value,true);
	}

	/**
	 * @return string the CSS class of the control
	 */
	public function getCssClass()
	{
		return $this->getViewState('CssClass','');
	}

	/**
	 * Sets the CSS class name of the control
	 * @param string the CSS class name to be set
	 */
	public function setCssClass($value)
	{
		$this->setViewState('CssClass',$value,'');
	}

	/**
	 * @return string the access key of the control
	 */
	public function getAccessKey()
	{
		return $this->getViewState('AccessKey','');
	}

	/**
	 * Sets the access key of the control.
	 * Only one-character string can be set, or an exception will be raised.
	 * Pass empty string if you want to disable access key.
	 * @param string the access key to be set
	 */
	public function setAccessKey($value)
	{
		if(strlen($value)>1)
			throw new Exception("Only one character is allowed for AccessKey.");
		$this->setViewState('AccessKey',$value,'');
	}

	/**
	 * @return integer the tab index of the control
	 */
	public function getTabIndex()
	{
		return $this->getViewState('TabIndex',0);
	}

	/**
	 * Sets the tab index of the control.
	 * Pass 0 if you want to disable tab index.
	 * @param integer the tab index to be set
	 */
	public function setTabIndex($value)
	{
		$this->setViewState('TabIndex',$value,0);
	}

	/**
	 * @return string the tooltip of the control
	 */
	public function getToolTip()
	{
		return $this->getViewState('ToolTip','');
	}

	/**
	 * Sets the tooltip of the control.
	 * Pass empty string if you want to disable tooltip.
	 * @param string the tooltip to be set
	 */
	public function setToolTip($value)
	{
		$this->setViewState('ToolTip',$value,'');
	}

	/**
	 * Parses a CSS style string into an array representation.
	 * @param string the CSS style string to be parsed
	 */
	public function parseStyle($str)
	{
		$style=array();
		$vs=explode(';',$str);
		foreach($vs as $vv)
		{
			$pos=strpos($vv,':');
			if($pos!==false)
			{
				$k=trim(substr($vv,0,$pos));
				$v=trim(substr($vv,$pos+1));
			}
			else
			{
				$k=trim($vv);
				$v='';
			}
			if(!strlen($k))
				continue;
			$style[$k]=$v;
		}
		return $style;
	}

	/**
	 * Sets the CSS style of the control.
	 * Note, the change is incremental and will overwrite existing style settings.
	 * To clear all the styles, pass a null value.
	 * @param string|array the CSS style to be set
	 */
	public function setStyle($value)
	{
		if(is_null($value))
		{
			$this->setViewState('Style',null,null);
			return;
		}
		if(is_string($value))
			$value=$this->parseStyle($value);
		if(is_array($value))
		{
			if(count($value)>0)
			{
				$style=array_merge($this->getViewState('Style',array()),$value);
				foreach($style as $key=>$value)
					if(empty($value))
						unset($style[$key]);
				if(count($style)==0)
					$style=null;
				$this->setViewState('Style',$style,null);
			}
		}
		else
			throw new Exception('Style can only be initialized with a string or an array.');
	}

	/**
	 * @return ArrayObject the CSS style of the control
	 */
	public function getStyle()
	{
		return $this->getViewState('Style',array());
	}

	/**
	 * @return string the width of the control
	 */
	public function getWidth()
	{
		return $this->getViewState('Width','');
	}

	/**
	 * Sets the width of the control
	 * @param string the width of the control
	 */
	public function setWidth($value)
	{
		$this->setViewState('Width',$value,'');
	}

	/**
	 * @return string the height of the control
	 */
	public function getHeight()
	{
		return $this->getViewState('Height','');
	}

	/**
	 * Sets the height of the control
	 * @param string the height of the control
	 */
	public function setHeight($value)
	{
		$this->setViewState('Height',$value,'');
	}

	/**
	 * @return string the foreground color of the control
	 */
	public function getForeColor()
	{
		return $this->getViewState('ForeColor','');
	}

	/**
	 * Sets the foreground color of the control
	 * @param string the foreground color of the control
	 */
	public function setForeColor($value)
	{
		$this->setViewState('ForeColor',$value,'');
	}

	/**
	 * @return string the background color of the control
	 */
	public function getBackColor()
	{
		return $this->getViewState('BackColor','');
	}

	/**
	 * Sets the background color of the control
	 * @param string the background color of the control
	 */
	public function setBackColor($value)
	{
		$this->setViewState('BackColor',$value,'');
	}

	/**
	 * @return string the border color of the control
	 */
	public function getBorderColor()
	{
		return $this->getViewState('BorderColor','');
	}

	/**
	 * Sets the border color of the control
	 * @param string the border color of the control
	 */
	public function setBorderColor($value)
	{
		$this->setViewState('BorderColor',$value,'');
	}

	/**
	 * @return string the border style of the control
	 */
	public function getBorderStyle()
	{
		return $this->getViewState('BorderStyle','');
	}

	/**
	 * Sets the border style of the control
	 * @param string the border style of the control
	 */
	public function setBorderStyle($value)
	{
		$this->setViewState('BorderStyle',$value,'');
	}

	/**
	 * @return string the border width of the control
	 */
	public function getBorderWidth()
	{
		return $this->getViewState('BorderWidth','');
	}

	/**
	 * Sets the border width of the control
	 * @param string the border width of the control
	 */
	public function setBorderWidth($value)
	{
		$this->setViewState('BorderWidth',$value,'');
	}
	
	/**
	 * Append a javascript statement to a particular attribute, e.g. "onclick".
	 * @param string the event attribute name, e.g. "onclick"
	 * @param string the javascript statement, e.g. alert('hello')
	 */
	public function appendJavascriptEvent($event, $js)
	{
		$events = $this->getJavascriptEvents();
		$events[$event][$js] = true;
		$this->setViewState('jsEvents',$events, '');
	}
	
	/**
	 * Remove all or a particular javascript statements.
	 * @param string the javascript event attribute name
	 * @param string|null the javascript statement to remove, null will remove all.
	 */
	public function removeJavascriptEvent($event, $js=null)
	{
		$events = $this->getJavascriptEvents();
		if(is_null($js))
		{
			if(isset($events[$event])) unset($events[$event]);
		}
		else
		{
			if(isset($events[$event]) && isset($events[$event][$js]))
				unset($events[$event][$js]);
		}
		$this->setViewState('jsEvents', $events, '');
	}
	
	/**
	 * Get the list of all the javascript statements.
	 * @return array list of javascript events and their statements. 
	 */
	public function getJavascriptEvents()
	{
		return $this->getViewState('jsEvents', '');
	}

	/**
	 * This overrides the parent implementation by rendering more TWebControl-specific attributes.
	 * @return ArrayObject the attributes to be rendered
	 */
	protected function getAttributesToRender()
	{
		$attributes=parent::getAttributesToRender();
		if(!$this->isEnabled())
			$attributes['disabled']="disabled";
		$tabIndex=$this->getTabIndex();
		if(!empty($tabIndex))
			$attributes['tabindex']=$tabIndex;
		$toolTip=$this->getToolTip();
		if(strlen($toolTip))
			$attributes['title']=$toolTip;
		$accessKey=$this->getAccessKey();
		if(strlen($accessKey))
			$attributes['accesskey']=$accessKey;
		$cssClass=$this->getCssClass();
		if(strlen($cssClass))
			$attributes['class']=$cssClass;

		$style=$this->getStyle();
		$width=$this->getWidth();
		if(!empty($width))
			$style['width']=$width;
		$height=$this->getHeight();
		if(!empty($height))
			$style['height']=$height;
		$foreColor=$this->getForeColor();
		if(strlen($foreColor))
			$style['color']=$foreColor;
		$backColor=$this->getBackColor();
		if(strlen($backColor))
			$style['background-color']=$backColor;
		$borderColor=$this->getBorderColor();
		if(strlen($borderColor))
			$style['border-color']=$borderColor;
		$borderWidth=$this->getBorderWidth();
		if(!empty($borderWidth))
			$style['border-width']=$borderWidth;
		$borderStyle=$this->getBorderStyle();
		if(strlen($borderStyle))
			$style['border-style']=$borderStyle;
		if(count($style)>0)
		{
			$s='';
			foreach($style as $k=>$v)
				$s.="$k:$v;";
			$attributes['style']=isset($attributes['style'])?trim($attributes['style'],';').";$s":$s;
		}
		
		//append the javascript events
		$jsEvents = $this->getJavascriptEvents();
		if(!empty($jsEvents) && is_array($jsEvents))
		{
			foreach($jsEvents as $event => $statements)
			{
				$javascripts = array();
				$attribute = $this->getAttribute($event);
				if(!empty($attribute))
					$javascripts = explode(';', $attribute);
									
				$javascripts = array_merge($javascripts, array_keys($statements));
				$attributes[$event] = implode(';', $javascripts);
			}
		}
		return $attributes;
	}
}

?>