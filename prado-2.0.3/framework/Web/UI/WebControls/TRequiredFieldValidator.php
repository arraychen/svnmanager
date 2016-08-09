<?php
/**
 * TRequiredFieldValidator class file
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
 * @version $Revision: 1.7 $  $Date: 2005/06/13 07:04:29 $
 * @package System.Web.UI.WebControls
 */

/**
 * TValidator class file
 */
require_once(dirname(__FILE__).'/TValidator.php');

/**
 * TRequiredFieldValidator class
 *
 * TRequiredFieldValidator makes the associated input component a required field.
 * The input component fails validation if its value does not change from
 * the <b>InitialValue</b> property upon losing focus.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>InitialValue</b>, string, kept in viewstate
 *   <br>Gets or sets the initial value of the associated input component.
 *   The associated input component fails validation if its value does not
 *   change from the InitialValue upon losing focus.
 *
 * Examples
 * - On a page template file, insert the following lines to test the validator,
 * <code>
 *   <com:TTextBox ID="username" />
 *   <com:TRequiredFieldValidator ControlToValidate="username" ErrorMessage="User name is required."/>
 * </code>
 * When the user submits the page, username input field will be automatically
 * validated by the validator. If no value is entered, the error message will be displayed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRequiredFieldValidator extends TValidator
{
	/**
	 * @return mixed the initial value of the associated input component.
	 */
	public function getInitialValue()
	{
		return $this->getViewState('InitialValue','');
	}

	/**
	 * Sets the initial value of the associated input component.
	 * @param mixed the initial value
	 */
	public function setInitialValue($value)
	{
		$this->setViewState('InitialValue',$value,'');
	}

	/**
	 * This method overrides the parent's implementation.
	 * The validation succeeds if the input component changes its data
	 * from the InitialValue or the input component is not given.
	 * @return boolean whether the validation succeeds
	 */
	public function evaluateIsValid()
	{
		$idPath=$this->getControlToValidate();
		if(strlen($idPath))
		{
			$control=$this->getTargetControl($idPath);
			$value=$control->getValidationPropertyValue();
			return is_null($value)?false:trim($value)!=trim($this->getInitialValue());
		}
		else
			return true;
	}
	
	/**
	 * Get a list of options for the client-side javascript validator
	 * @return array list of options for the validator 
	 */
	protected function getJsOptions()
	{
		$options = parent::getJsOptions();
		$options['initialvalue']=$this->getInitialValue();
		return $options;
	}
}

?>