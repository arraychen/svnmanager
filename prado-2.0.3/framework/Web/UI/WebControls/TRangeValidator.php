<?php
/**
 * TRangeValidator class file
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
 * @version $Revision: 1.8 $  $Date: 2005/06/13 07:04:28 $
 * @package System.Web.UI.WebControls
 */

/**
 * TValidator class file
 */
require_once(dirname(__FILE__).'/TValidator.php');

/**
 * TRangeValidator class
 *
 * TRangeValidator tests whether the value of an input component is within a specified range.
 *
 * The TRangeValidator component uses three key properties to perform its validation.
 * The <b>MinValue</b> and <b>MaxValue</b> properties specify the minimum and maximum values
 * of the valid range. The <b>ValueType</b> property is used to specify the data type of
 * the values to compare. The values to compare are converted to this data type before
 * the validation operation is performed. The following value types are supported:
 * - <b>Integer</b> A 32-bit signed integer data type.
 * - <b>Double</b> A double-precision floating point number data type.
 * - <b>Currency</b> A decimal data type that can contain currency symbols.
 * - <b>Date</b> A date data type. The format follows the GNU date syntax.
 * - <b>String</b> A string data type.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>MinValue</b>, string, kept in viewstate
 *   <br>Gets or sets the minimum value of the validation range.
 * - <b>MaxValue</b>, string, kept in viewstate
 *   <br>Gets or sets the maximum value of the validation range.
 * - <b>ValueType</b>, string, default=String, kept in viewstate
 *   <br>Gets or sets the data type (Integer, Double, Currency, Date, String)
 *   that the values being compared are converted to before the comparison is made.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TRangeValidator extends TValidator
{
	/**
	 * @return string the minimum value of the validation range.
	 */
	public function getMinValue()
	{
		return $this->getViewState('MinValue','');
	}

	/**
	 * Sets the minimum value of the validation range.
	 * @param string the minimum value
	 */
	public function setMinValue($value)
	{
		$this->setViewState('MinValue',$value,'');
	}

	/**
	 * @return string the maximum value of the validation range.
	 */
	public function getMaxValue()
	{
		return $this->getViewState('MaxValue','');
	}

	/**
	 * Sets the maximum value of the validation range.
	 * @param string the maximum value
	 */
	public function setMaxValue($value)
	{
		$this->setViewState('MaxValue',$value,'');
	}

	/**
	 * @return string the data type that the values being compared are 
	 * converted to before the comparison is made.
	 */
	public function getValueType()
	{
		return $this->getViewState('ValueType','String');
	}

	/**
	 * Sets the data type (Integer, Double, Currency, Date, String) that the values 
	 * being compared are converted to before the comparison is made.
	 * @param string the data type
	 */
	public function setValueType($value)
	{
		if($value!='Integer' && $value!='Double' && $value!='Date' && $value!='Currency')
			$value='String';
		$this->setViewState('ValueType',$value,'String');
	}

	/**
     * Sets the date format for a date validation
     * @param string the date format value
     */
	public function setDateFormat($value)
	{
		$this->setViewState('DateFormat', $value, '');
	}
   
	/**
	 * @return string the date validation date format if any
	 */
	public function getDateFormat()
	{
		return $this->getViewState('DateFormat', '');
	}

	/**
	 * This method overrides the parent's implementation.
	 * The validation succeeds if the input data is within the range.
	 * The validation always succeeds if ControlToValidate is not specified
	 * or the input data is empty.
	 * @return boolean whether the validation succeeds
	 */
	public function evaluateIsValid()
	{
		$idPath=$this->getControlToValidate();
		if(strlen($idPath))
		{
			$control=$this->getTargetControl($idPath);
			$value=$control->getValidationPropertyValue();
			if(!strlen($value))
				return true;
		}
		else
			return true;

		switch($this->getValueType())
		{
			case 'Integer':
				return $this->isValidInteger($value);
			case 'Float':
			case 'Double':
				return $this->isValidDouble($value);
			case 'Currency':
				return $this->isValidCurrency($value);
			case 'Date':
				return $this->isValidDate($value);
			default:
				return $this->isValidString($value);
		}
	}

	/**
	* Determine if the value is within the integer range.
	* @param string value to validate true 
	* @return boolean true if within integer range.
	*/	
	protected function isValidInteger($value)
	{
		$minValue=$this->getMinValue();
		$maxValue=$this->getMaxValue();

		$value=intval($value);
		$valid=true;
		if(strlen($minValue))
			$valid=$valid && ($value>=intval($minValue));
		if(strlen($maxValue))
			$valid=$valid && ($value<=intval($maxValue));
		return $valid;
	}

	/**
	 * Determine if the value is within the specified double range.
	 * @param string value to validate
	 * @return boolean true if within range. 
	 */
	protected function isValidDouble($value)
	{
		$minValue=$this->getMinValue();
		$maxValue=$this->getMaxValue();

		$value=floatval($value);
		$valid=true;
		if(strlen($minValue))
			$valid=$valid && ($value>=floatval($minValue));
		if(strlen($maxValue))
			$valid=$valid && ($value<=floatval($maxValue));
		return $valid;
	}

	/**
	 * Determine if the value is a valid currency range,
	 * @param string currency value
	 * @return boolean true if within range. 
	 */
	protected function isValidCurrency($value)
	{
		$minValue=$this->getMinValue();
		$maxValue=$this->getMaxValue();

		$valid=true;
		$value = $this->getCurrencyValue($value);
		if(strlen($minValue))
			$valid=$valid && ($value>= $this->getCurrencyValue($minValue));
		if(strlen($maxValue))		
			$valid=$valid && ($value<= $this->getCurrencyValue($minValue));
		return $valid;
	}

	/**
	 * Parse the string into a currency value, return the float value of the currency.
	 * @param string currency as string
	 * @return float currency value.
	 */
	protected function getCurrencyValue($value)
	{
		if(preg_match('/[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?/',$value,$matches))
			return floatval($matches[0]);
		else
			return 0.0;
	}

	/**
	 * Determine if the date is within the specified range.
	 * Uses pradoParseDate and strtotime to get the date from string.
	 * @param string date as string to validate
	 * @return boolean true if within range. 
	 */
	protected function isValidDate($value)
	{
		$minValue=$this->getMinValue();
		$maxValue=$this->getMaxValue();
		   
		$valid=true;

		$dateFormat = $this->getDateFormat();
		if (strlen($dateFormat)) 
		{
			$value = pradoParseDate($value, $dateFormat);
			if (strlen($minValue)) 
				$valid=$valid && ($value>=pradoParseDate($minValue, $dateFormat));   
			if (strlen($maxValue)) 
				$valid=$valid && ($value <= pradoParseDate($maxValue, $dateFormat));
			return $valid;
		} 
		else 
		{
			$value=strtotime($value);
			if(strlen($minValue))
				$valid=$valid && ($value>=strtotime($minValue));
			if(strlen($maxValue))
				$valid=$valid && ($value<=strtotime($maxValue));
			return $valid;
		} 
	}

	/**
	 * Compare the string with a minimum and a maxiumum value.
	 * Uses strcmp for comparision.
	 * @param string value to compare with.
	 * @return boolean true if the string is within range. 
	 */
	protected function isValidString($value)
	{
		$minValue=$this->getMinValue();
		$maxValue=$this->getMaxValue();
		   
		$valid=true;
		if(strlen($minValue))
			$valid=$valid && (strcmp($value,$minValue)>=0);
		if(strlen($maxValue))
			$valid=$valid && (strcmp($value,$maxValue)<=0);
		return $valid;
	}

	/**
	 * Returns the attributes to be rendered as javascript.
	 * This method overrides the parent's implementation.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getJsOptions()
	{
		$options=parent::getJsOptions();
		if($this->isClientScriptEnabled())
		{
			$options['minimumvalue']=$this->getMinValue();
			$options['maximumvalue']=$this->getMaxValue();
			$options['type']=$this->getValueType();
			$dateFormat = $this->getDateFormat();
			if(strlen($dateFormat))
				$options['dateformat'] = $dateFormat;
		}
		return $options;
	}
}

?>