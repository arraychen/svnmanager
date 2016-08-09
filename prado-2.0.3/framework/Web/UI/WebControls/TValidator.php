<?php
/**
 * TValidator class file
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
 * @version $Revision: 1.27 $  $Date: 2005/08/05 03:10:57 $
 * @package System.Web.UI.WebControls
 */

/**
 * TValidator class
 *
 * TValidator serves as the base class for validation components.
 *
 * Validation is performed when a button component, such a TButton, a TLinkButton
 * or a TImageButton is clicked and the <b>CausesValidation</b> of these components is true.
 * You can also manually perform validation by using the validate() method of the TPage class.
 *
 * Validator components always validate the associated input component on the server.
 * TValidation components also have complete client-side implementation that allow
 * DHTML supported browsers to perform validation on the client via Javascript.
 * Client-side validation will validate user input before it is sent to the server.
 * The form data will not be submitted if any error is detected. This avoids
 *  the round-trip of information necessary for server-side validation.
 *
 * You can use multiple validator components to validate an individual input component,
 * each responsible for validating different criteria. For example, on a user registration
 * form, you may want to make sure the user enters a value in the username text box,
 * and the input must consist of only word characters. You can use a TRequiredFieldValidator
 * to ensure the input of username and a TRegularExpressionValidator to ensure the proper
 * input.
 *
 * If an input component fails validation, the text specified by the <b>ErrorMessage</b>
 * property is displayed in the validation component. If the <b>Text</b> property is set
 * it will be displayed instead, however. If both <b>ErrorMessage</b> and <b>Text</b>
 * are empty, the body content of the validator will be displayed.
 *
 * You can also place a <b>TValidationSummary</b> component on the page to display error messages
 * from the validators together. In this case, only the <b>ErrorMessage</b> property of the
 * validators will be displayed in the TValidationSummary component.
 *
 * Note, the <b>IsValid</b> property of the current TPage instance will be automatically
 * updated by the validation process which occurs after <b>OnLoad</b> of TPage and
 * before the postback events. Therefore, if you use the <b>IsValid</b>
 * property in the <b>OnLoad</b> event of TPage, you must first explicitly call
 * the validate() method of TPage. As an alternative, you can place your code
 * in the postback event handler, such as <b>OnClick</b> or <b>OnCommand</b>,
 * instead of <b>OnLoad</b> event.
 *
 * Note, to use validators derived from this component, you have to 
 * copy the file "<framework>/js/prado_validator.js" to the "js" directory
 * which should be under the directory containing the entry script file.
 *
 * <b>Notes to Inheritors</b>  When you inherit from the TValidator class,
 * you must override the method {@link evaluateIsValid}.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>EnableClientScript</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets a value indicating whether client-side validation is enabled.
 * - <b>Display</b>, string, default=Static, kept in viewstate
 *   <br>Gets or sets the display behavior (None, Static, Dynamic) of the error message in a validation component.
 * - <b>ControlToValidate</b>, string, kept in viewstate
 *   <br>Gets or sets the input component to validate. This property must be set to
 *   the ID path of an input component. The ID path is the dot-connected IDs of
 *   the components reaching from the validator's parent component to the target component.
 *   For example, if HomePage is the parent of Validator and SideBar components, and
 *   SideBar is the parent of UserName component, then the ID path for UserName
 *   would be "SideBar.UserName" if UserName is to be validated by Validator.
 * - <b>Text</b>, string, kept in viewstate
 *   <br>Gets or sets the text of TValidator control.
 * - <b>ErrorMessage</b>, string, kept in viewstate
 *   <br>Gets or sets the text for the error message.
 * - <b>EncodeText</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets the value indicating whether Text and ErrorMessage should be HTML-encoded when rendering.
 * - <b>IsValid</b>, boolean, default=true
 *   <br>Gets or sets a value that indicates whether the associated input component passes validation.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
abstract class TValidator extends TWebControl implements IValidator
{
	/**
	 * URL (either relative or absolute) of javascript file that contains functions for client-side validation
	 */
	//const JS_VALIDATOR='validator.js';

	/**
	 * whether the validation succeeds
	 * @var boolean
	 */
	private $isValid=true;

	/**
	 * This method overrides parent's implementation by setting {@link isValid} to true if disabled.
	 * @param boolean whether the component is enabled.
	 */
	public function setEnabled($value)
	{
		parent::setEnabled($value);
		if(!$value)
			$this->isValid=true;
	}

	/**
	 * @return string the display behavior (None, Static, Dynamic) of the error message in a validation component.
	 */
	public function getDisplay()
	{
		return $this->getViewState('Display','Static');
	}

	/**
	 * Sets the display behavior (None, Static, Dynamic) of the error message in a validation component.
	 * @param string the display behavior (None, Static, Dynamic)
	 */
	public function setDisplay($value)
	{
		if($value!='None' && $value!='Dynamic')
			$value='Static';
		$this->setViewState('Display',$value,'Static');
	}

	/**
	 * @return boolean whether client-side validation is enabled.
	 */
	public function isClientScriptEnabled()
	{
		return $this->getViewState('EnableClientScript',true);
	}

	/**
	 * Sets the value indicating whether client-side validation is enabled.
	 * @param boolean whether client-side validation is enabled.
	 */
	public function enableClientScript($value)
	{
		$this->setViewState('EnableClientScript',$value,true);
	}

	/**
	 * @return string the text caption of the validator
	 */
	public function getText()
	{
		return $this->getViewState('Text','');
	}

	/**
	 * Sets the text content of the validator.
	 * @param string the text of the validator to be set
	 */
	public function setText($value)
	{
		$this->setViewState('Text',$value,'');
	}

	/**
	 * @return string the text for the error message.
	 */
	public function getErrorMessage()
	{
		return $this->getViewState('ErrorMessage','');
	}

	/**
	 * Sets the text for the error message.
	 * @param string the error message
	 */
	public function setErrorMessage($value)
	{
		$this->setViewState('ErrorMessage',$value,'');
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
	 * @return string the ID path of the input component to validate
	 */
	public function getControlToValidate()
	{
		return $this->getViewState('ControlToValidate','');
	}

	/**
	 * Sets the ID path of the input component to validate
	 * @param string the ID path
	 */
	public function setControlToValidate($value)
	{
		$this->setViewState('ControlToValidate',$value,'');
	}
	
	/**
	 * Get the anchor href link for the error messages.
	 * @return string anchor string ID 
	 */
	public function getAnchor()
	{
		return $this->getViewState('Anchor', '');
	}
	
	/**
	 * Set the anchor ID for the error message link. If the value
	 * is "true" then the ID of the ControlToValidate will be used
	 * otherwise the given value will be used as the anchor.
	 * @param string anchor string ID
	 */
	public function setAnchor($value)
	{
		$this->setViewState('Anchor', $value, '');
	}
	
	/**
	 * Get the CssClass for the ControlToValidate when the validation
	 * failes. The CSS is appended to the control.
	 * @return string the CSS class to append to the control.
	 */
	public function getControlCssClass()
	{
		return $this->getViewState('ControlCssClass', '');
	}
	
	/**
	 * Set the CssClass for the ControlToValidate component when the 
	 * validation fails.
	 * @param string the CSS class name.
	 */
	public function setControlCssClass($value)
	{
		$this->setViewState('ControlCssClass', $value, '');
	}

	/**
	 * @param string the ID path of the component to be validated
	 * @return IPostBackDataHandler the input component to be validated
	 */
	public function getTargetControl($idPath)
	{
		$parent=$this->getParent();
		$control=$parent->findObject($idPath);
		if(is_null($control))
			throw new Exception("Invalid ControlToValidate value: $idPath.");
		if(($control instanceof IPostBackDataHandler))
			return $control;
		else
			throw new Exception("Component to be validated must implement IPostBackDataHandler interface.");
	}

	/**
	 * @return boolean whether the validation succeeds
	 */
	public function isValid()
	{
		return $this->isValid;
	}

	/**
	 * Sets the value indicating whether the validation succeeds
	 * @param boolean whether the validation succeeds
	 */
	public function setValid($value)
	{
		$this->isValid=$value;
	}

	/**
	 * Validates the specified component.
	 * Do not override this method. Override {@link evaluateIsValid} instead.
	 * @return boolean whether the validation succeeds
	 */
	final public function validate()
	{
		if($this->isVisible() && $this->isEnabled() && strlen($this->getControlToValidate()))
		{
			$valid=$this->evaluateIsValid();
			$this->setValid($valid);
			return $valid;
		}
		else
		{
			$this->setValid(true);
			return true;
		}
	}

	/**
	 * This is the major method for validation.
	 * Derived classes should override this method to provide customized validation.
	 * The default implementation is simply returning true.
	 * @return boolean whether the validation succeeds
	 */
	public function evaluateIsValid()
	{
		return true;
	}

	/**
	 * Overrides parent implementation by registering necessary Javascripts for validation.
	 * @param TEventParameter the event parameter
	 */
	public function onPreRender($param)
	{
		parent::onPreRender($param);
		$page=$this->getPage();
		if($this->isClientScriptEnabled() && !$page->isScriptFileRegistered('validator'))
		{
			$dependencies = array('base', 'dom', 'validator');
			$path = $this->Application->getResourceLocator()->getJsPath().'/';
			foreach($dependencies as $file)
				$page->registerScriptFile($file,$path.$file.'.js');

			$script="
			if(typeof(Prado) == 'undefined')
				alert(\"Unable to find Prado javascript library '{$path}base.js'.\");
			else if(Prado.Version != 2.0) 
				alert(\"Prado javascript library version 2.0 required.\");
			else if(typeof(Prado.Validation) == 'undefined')
				alert(\"Unable to find validation javascript library '{$path}validator.js'.\");				
			else
				Prado.Validation.AddForm('{$page->Form->ClientID}');				
			";
			$page->registerEndScript('TValidator',$script);
		}
	
		//update the Css class for the controls
		$idPath=$this->getControlToValidate();
		if(strlen($idPath))
			$this->updateControlCssClass($this->getTargetControl($idPath));
	}
	
	/**
	 * Update the ControlToValidate component's css class depending
	 * if the ControlCssClass property is set, and whether this is valid.
	 * @param object the control to update the css class.
	 * @return boolean true if change, false otherwise.
	 */
	protected function updateControlCssClass($control)
	{
		//do the CssClass change to the control
		$CssClass = $this->getControlCssClass();
		
		if(strlen($CssClass) <= 0) return false;
		
		if(!($control instanceof TWebControl)) return false;
		
		$class = preg_replace ('/ '.preg_quote($CssClass).'/', '',$control->getCssClass());
		
		if(!$this->isValid())
			$class .= ' '.$CssClass;

		$control->setCssClass($class);
		
		return true;
	}

	/**
	 * Returns the error message of which an link anchor and javascript
	 * focus call is added if the property Anchor is set.
	 * The Anchor can be set to true or to a particular html element ID.
	 * @param string the error message to add anchor link to
	 * @return string error message with anchor link if Anchor property is set
	 * to true or a non-empty string.
	 */
	public function getAnchoredMessage($message)
	{
		$anchor = $this->getAnchor();
		if(empty($anchor)) return $message;

		$idPath=$this->getControlToValidate();
		if(strtolower($anchor) == 'true' && strlen($idPath))
		{
			$idPath=$this->getControlToValidate();
			$anchor = $this->getTargetControl($idPath)->getClientID();
		}		
	
		$js = "onclick=\"javascript: return Prado.Validation.Util.focus('{$anchor}');\"";

		if(!empty($anchor))
			return "<a href=\"#{$anchor}\" {$js} >{$message}</a>";
		else
			return $message;		
	}
	
	/**
	 * Render the client-side javascript code.
	 * @param string a list of options for the client-side validator
	 */
	protected function renderJsValidator($options)
	{
		if(!$this->isEnabled() || !$this->isClientScriptEnabled())
			return;
		$class = get_class($this); //validator name
		$option = $this->renderJsOptions($options);
		$script = "new Prado.Validation(Prado.Validation.{$class}, {$option});";
		$this->Page->registerEndScript($options['id'].'jsValidator', $script);
	}

	/**
	 * Render the array as javascript list.
	 * @param array list of options.
	 * @return string array as javascript list. 
	 */
	protected function renderJsOptions($options)
	{
		$keyPair = array();
		foreach($options as $key => $value)
			$keyPair[] = $key.':"'.$this->escapeJS($value).'"';
		return '{'.implode(', ', $keyPair).'}';		
	}

	/**
	 * Escape javascript strings.
	 */
	protected function escapeJS($string)
	{
		return str_replace(array("\n","\r"),array('\n',''),addslashes($string));
	}

	/**
	 * Get a list of options for the client-side javascript validator
	 * @return array list of options for the validator 
	 */
	protected function getJsOptions()
	{
		$options['id'] = $this->ClientID;
		$idPath=$this->getControlToValidate();
		if(strlen($idPath))
			$options['controltovalidate']=$this->getTargetControl($idPath)->getClientID();

		$msg = $this->getErrorMessage();					
		if(strlen($msg))
			$options['errormessage']=$msg;

		$display=$this->getDisplay();
		if($display!='Static')
			$options['display']=$display;
		if(!$this->isValid())
			$options['isvalid']='False';
		if(!$this->isEnabled())
			$options['enabled']='False';
		$CssClass = $this->getControlCssClass();
		if(strlen($CssClass))
			$options['controlcssclass'] = $CssClass;
		return $options;
	}

	/**
	 * Get the validation error message.
	 * @return string error message 
	 */
	protected function getMessage()
	{
		$text = $this->getText();
		$text = $this->isEncodeText()?pradoEncodeData($text):$text;
		$msg = $this->getErrorMessage();
		$msg = $this->getAnchoredMessage($this->isEncodeText()?pradoEncodeData($msg):$msg);		

		if(strlen($text))
			return $text;
		else if(strlen($msg))
			return $msg;
		else
			return $this->renderBody();
	}

	/**
	 * Overrides parent implementation by rendering TValidator-specific presentation.
	 * @return string the rendering result
	 */
	public function render()
	{
		$display=$this->getDisplay();

		if(!$this->isClientScriptEnabled() && ($this->isValid() || $display=='None'))
			return '';

		$visible= $this->isEnabled() && !$this->isValid();
		$style=array();
		if($display=='None' || (!$visible && $display=='Dynamic'))
			$style['display']='none';
		else if(!$visible)
			$style['visibility']='hidden';

		$this->setStyle($style);
		
		//render the javascripts
		$this->renderJsValidator($this->getJsOptions());

		if($display == 'None') return;

		$content='<span '.$this->renderAttributes().'>';
		$content.=$this->getMessage();
		$content.='</span>';

		return $content;
	}
}

?>