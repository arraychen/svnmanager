<?php
/**
 * TCompareValidator class file
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
 * @version $Revision: 1.10 $  $Date: 2005/06/13 07:04:28 $
 * @package System.Web.UI.WebControls
 */

/**
 * TValidator class file
 */
require_once(dirname(__FILE__).'/TValidator.php');

/**
 * TCompareValidator class
 *
 * TCompareValidator compares the value entered by the user into an input component with the value
 * entered into another input component or a constant value. To specify the input component to
 * validate, set the <b>ControlToValidate</b> property to the ID of the input component.
 *
 * To compare the associated input component with another input component,
 * set the <b>ControlToCompare</b> property to the ID of the component to compare with.
 *
 * To compare the associated input component with a constant value,
 * specify the constant value to compare with by setting the <b>ValueToCompare</b> property.
 *
 * The <b>ValueType</b> property is used to specify the data type of both comparison values.
 * Both values are automatically converted to this data type before the comparison operation
 * is performed. The following value types are supported:
 * - <b>Integer</b> A 32-bit signed integer data type.
 * - <b>Double</b> A double-precision floating point number data type.
 * - <b>Currency</b> A decimal data type that can contain currency symbols.
 * - <b>Date</b> A date data type. The format follows the GNU date syntax.
 * - <b>String</b> A string data type.
 *
 * Use the <b>Operator</b> property to specify the type of comparison to perform,
 * such as Equal, NotEqual, GreaterThan, GreaterThanEqual, LessThan, LessThanEqual, DataTypeCheck.
 * If you set the <b>Operator</b> property to DataTypeCheck, the TCompareValidator component
 * will ignore the <b>ControlToCompare</b> and <b>ValueToCompare</b> properties and simply
 * indicates whether the value entered into the input component can be converted to the data type
 * specified by the <b>ValueType</b> property.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>ControlToCompare</b>, string, kept in viewstate
 *   <br>Gets or sets the input component to compare with the input control being validated.
 * - <b>ValueToCompare</b>, string, kept in viewstate
 *   <br>Gets or sets a constant value to compare with the value entered by the user into the input component being validated.
 * - <b>ValueType</b>, string, default=String, kept in viewstate
 *   <br>Gets or sets the data type (Integer, Double, Currency, Date, String)
 *   that the values being compared are converted to before the comparison is made.
 * - <b>Operator</b>, string, default=Equal, kept in viewstate
 *   <br>Gets or sets the comparison operation to perform (Equal, NotEqual, GreaterThan, GreaterThanEqual, LessThan, LessThanEqual, DataTypeCheck)
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TCompareValidator extends TValidator
{
	/**
	 * @return string the data type that the values being compared are converted to before the comparison is made.
	 */
	public function getValueType()
	{
		return $this->getViewState('ValueType','String');
	}

	/**
	 * Sets the data type (Integer, Double, Currency, Date, String) that the values being compared are converted to before the comparison is made.
	 * @param string the data type
	 */
	public function setValueType($value)
	{
		if($value!='Integer' && $value!='Double' && $value!='Date' && $value!='Currency')
			$value='String';
		$this->setViewState('ValueType',$value,'String');
	}

	/**
	 * @return string the input component to compare with the input control being validated.
	 */
	public function getControlToCompare()
	{
		return $this->getViewState('ControlToCompare','');
	}

	/**
	 * Sets the input component to compare with the input control being validated.
	 * @param string the ID path of the component to compare with
	 */
	public function setControlToCompare($value)
	{
		$this->setViewState('ControlToCompare',$value,'');
	}

	/**
	 * @return string the constant value to compare with the value entered by the user into the input component being validated.
	 */
	public function getValueToCompare()
	{
		return $this->getViewState('ValueToCompare','');
	}

	/**
	 * Sets the constant value to compare with the value entered by the user into the input component being validated.
	 * @param string the constant value
	 */
	public function setValueToCompare($value)
	{
		$this->setViewState('ValueToCompare',$value,'');
	}

	/**
	 * @return string the comparison operation to perform (Equal, NotEqual, GreaterThan, GreaterThanEqual, LessThan, LessThanEqual, DataTypeCheck)
	 */
	public function getOperator()
	{
		return $this->getViewState('Operator','Equal');
	}

	/**
	 * Sets the comparison operation to perform (Equal, NotEqual, GreaterThan, GreaterThanEqual, LessThan, LessThanEqual, DataTypeCheck)
	 * @param string the comparison operation
	 */
	public function setOperator($value)
	{
		if($value!='NotEqual' && $value!='GreaterThan' && $value!='GreaterThanEqual' && $value!='LessThan' && $value!='LessThanEqual' && $value!='DataTypeCheck')
			$value='Equal';
		$this->setViewState('Operator',$value,'Equal');
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
	 * The validation succeeds if the input data compares successfully.
	 * The validation always succeeds if ControlToValidate is not specified
	 * or the input data is empty.
	 * @return boolean whether the validation succeeds
	 */
	public function evaluateIsValid()
	{
		$idPath=$this->getControlToValidate();
		if(!strlen($idPath))
			return true;
		$control=$this->getTargetControl($idPath);
		$value=$control->getValidationPropertyValue();
		if(!strlen($value))
			return true;
		
		if($this->getOperator() == 'DataTypeCheck')
			return $this->evaluateDataTypeCheck($value);
		
		$controlToCompare=$this->getControlToCompare();
		if(strlen($controlToCompare))
		{
			$control2=$this->getTargetControl($controlToCompare);
			$value2=$control2->getValidationPropertyValue();
			if(is_null($value2))
				return false;
		}
		else
			$value2=$this->getValueToCompare();
		
		$values = $this->getComparisonValues($value, $value2);
		switch($this->getOperator()) 
		{
			case 'Equal':
				return $values[0] == $values[1];
			case 'NotEqual':
				return $values[0] != $values[1];
			case 'GreaterThan':
				return $values[0] > $values[1];
			case 'GreaterThanEqual':
				return $values[0] >= $values[1];
			case 'LessThan':
				return $values[0] < $values[1];
			case 'LessThanEqual':
				return $values[0] <= $values[1];
		}

		return false;
	}

	/**
	 * Determine if the given value is of a particular type using RegExp.
	 * @param string value to check
	 * @return boolean true if value fits the type expression. 
	 */
	protected function evaluateDataTypeCheck($value)
	{
		switch($this->getValueType())
		{
			case 'Integer':
				return preg_match('/^[-+]?[0-9]+$/',trim($value));
			case 'Float':
			case 'Double':
				return preg_match('/^[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/',trim($value));
			case 'Currency':
				return preg_match('/[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/',trim($value));
			case 'Date':
				$dateFormat = $this->getDateFormat();
				if(strlen($dateFormat))
					return pradoParseDate($value, $dateFormat) !== null;
				else
					return strtotime($value) > 0;
		}
		return true;
	}

	/**
	 * Parse the pair of values into the appropriate value type.
	 * @param string value one
	 * @param string second value
	 * @return array appropriate type of the value pair, array($value1, $value2);
	 */
	protected function getComparisonValues($value1, $value2)
	{
		switch($this->getValueType())
		{
			case 'Integer':
				return array(intval($value1), intval($value2));
			case 'Float':
			case 'Double':
				return array(floatval($value1), floatval($value2));
			case 'Currency':
				if(preg_match('/[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?/',$value1,$matches))
					$value1=floatval($matches[0]);
				else
					$value1=0;
				if(preg_match('/[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?/',$value2,$matches))
					$value2=floatval($matches[0]);
				else
					$value2=0;
				return array($value1, $value2);
			case 'Date':
				$dateFormat = $this->getDateFormat();
				if (strlen($dateFormat)) 
					return array(pradoParseDate($value1, $dateFormat), pradoParseDate($value2, $dateFormat));
				else
					return array(strtotime($value1), strtotime($value2));
		}
		return array($value1, $value2);
	}

	/**
	 * Get a list of options for the client-side javascript validator
	 * @return array list of options for the validator 
	 */
	protected function getJsOptions()
	{
		$options = parent::getJsOptions();
		$name=$this->getControlToCompare();
		if(strlen($name))
		{
			$id=$this->getTargetControl($name)->getClientID();
			$options['controltocompare']=$id;
			$options['controlhookup']=$id;
		}
		$value=$this->getValueToCompare();
		if(strlen($value))
			$options['valuetocompare']=$value;
		$operator=$this->getOperator();
		if($operator!=='Equal')
			$options['operator']=$operator;
		$options['type']=$this->getValueType();
		$dateFormat = $this->getDateFormat();
		if(strlen($dateFormat))
			$options['dateformat'] = $dateFormat;

		return $options;
	}

	/**
	 * Update the control to compare Css class. Override and calls parent onPreRender.
	 */
	public function onPreRender($param)
	{
		parent::onPreRender($param);
		$controlToCompare=$this->getControlToCompare();
		if(strlen($controlToCompare))
			$this->updateControlCssClass($this->getTargetControl($controlToCompare));
	}
}

?>