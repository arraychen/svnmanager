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
 * @version $Revision: 1.6 $  $Date: 2005/06/13 07:04:29 $
 * @package System.Web.UI.WebControls
 */

/**
 * TValidator class file
 */
require_once(dirname(__FILE__).'/TValidator.php');

/**
 * TRegularExpressionValidator class
 *
 * TRegularExpressionValidator validates whether the value of an associated
 * input component matches the pattern specified by a regular expression.
 *
 * You can specify the regular expression by setting the <b>RegularExpression</b>
 * property. Some commonly used regular expressions include:
 * <pre>
 * French Phone Number: (0( \d|\d ))?\d\d \d\d(\d \d| \d\d )\d\d
 * French Postal Code: \d{5}
 * German Phone Number: ((\(0\d\d\) |(\(0\d{3}\) )?\d )?\d\d \d\d \d\d|\(0\d{4}\) \d \d\d-\d\d?)
 * German Postal Code: (D-)?\d{5}
 * Email Address: \w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*
 * Internal URL: http://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?
 * Japanese Phone Number: (0\d{1,4}-|\(0\d{1,4}\) ?)?\d{1,4}-\d{4}
 * Japanese Postal Code: \d{3}(-(\d{4}|\d{2}))?
 * P.R.C. Phone Number: (\(\d{3}\)|\d{3}-)?\d{8}
 * P.R.C. Postal Code: \d{6}
 * P.R.C. Social Security Number: \d{18}|\d{15}
 * U.S. Phone Number: ((\(\d{3}\) ?)|(\d{3}-))?\d{3}-\d{4}
 * U.S. ZIP Code: \d{5}(-\d{4})?
 * U.S. Social Security Number: \d{3}-\d{2}-\d{4}
 * </pre>
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>RegularExpression</b>, string, kept in viewstate
 *   <br>Gets or sets the regular expression that determines the pattern used to validate a field.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRegularExpressionValidator extends TValidator
{
	/**
	 * @return string the regular expression that determines the pattern used to validate a field.
	 */
	public function getRegularExpression()
	{
		return $this->getViewState('RegularExpression','');
	}

	/**
	 * Sets the regular expression that determines the pattern used to validate a field.
	 * @param string the regular expression
	 */
	public function setRegularExpression($value)
	{
		$this->setViewState('RegularExpression',$value,'');
	}

	/**
	 * This method overrides the parent's implementation.
	 * The validation succeeds if the input data matches the regular expression.
	 * The validation always succeeds if ControlToValidate is not specified
	 * or the regular expression is empty, or the input data is empty.
	 * @return boolean whether the validation succeeds
	 */
	public function evaluateIsValid()
	{
		$idPath=$this->getControlToValidate();
		$expression=$this->getRegularExpression();
		if(strlen($idPath) && strlen($expression))
		{
			$control=$this->getTargetControl($idPath);
			$value=$control->getValidationPropertyValue();
			return strlen($value)?preg_match("/^$expression\$/",$value):true;
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
		$options=parent::getJsOptions();
		$options['validationexpression']=$this->getRegularExpression();
		return $options;
	}
}

?>