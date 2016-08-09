<?php
/**
 * TRequiredListValidator class file
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/06/13 07:04:29 $
 * @package System.Web.UI.WebControls
 */

/**
 * TRequiredListValidator class.
 * 
 * TRequiredListValidator checks the number of selection and their values
 * for a <b>TListControl that allows multiple selection</b>. This validator
 * will only work for the components with the name attribute ending with "[]".
 *
 * You can specify the minimum or maximum (or both) number of selections
 * required using the <tt>MinSelection</tt> and <tt>MaxSelection</tt> 
 * properties, respectively. In addition, you can specify a comma separated
 * list of required selected values via the <tt>RequiredSelections</tt> property.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>MinSelection</b>, integer, kept in viewstate
 *   <br>Gets or sets the minimum number of selections required.
 * - <b>MaxSelection</b>, integer, kept in viewstate
 *   <br>Gets or sets the maximum number of selections required.
 * - <b>RequiredSelections</b>, string, kept in viewstate
 *   <br>Gets or sets the comma separated list of required values
 *   to be selected.
 *
 * Examples
 * - At least two selections
 * </code>
 *	<com:TListBox ID="listbox" SelectionMode="Multiple">
 *		<com:TListItem Text="item1" Value="value1" />
 *		<com:TListItem Text="item2" Value="value2" />
 *		<com:TListItem Text="item3" Value="value3" />
 *	</com:TListBox>
 *
 *	<com:TRequiredListValidator 
 *		ControlToValidate="listbox"
 *		MinSelection="2" 
 *		ErrorMessage="Please select at least 2" />
 * </code>
 * - "value1" must be selected <b>and</b> at least 1 other
 * <code>
 *	<com:TCheckBoxList ID="checkboxes">
 *		<com:TListItem Text="item1" Value="value1" />
 *		<com:TListItem Text="item2" Value="value2" />
 *		<com:TListItem Text="item3" Value="value3" />		
 *	</com:TCheckBoxList>
 *
 *	<com:TRequiredListValidator 
 *		ControlToValidate="checkboxes"
 *		RequiredSelections="value1"
 *		MinSelection="2"
 *		ErrorMessage="Please select 'item1' and at least 1 other" /> 
 * </code>
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Wed Jan 26 16:17:34 EST 2005
 * @package System.Web.UI.WebControls
 */
class TRequiredListValidator extends TValidator
{	
	/**
	 * Get the minimum number of selections required.
	 * @return int min number of selections 
	 */
	function getMinSelection()
	{
		return $this->getViewState('MinSelection',log(0));
	}
	
	/**
	 * Set the minimum number of selections.
	 * @param int minimum number of selections.
	 */
	function setMinSelection($value)
	{
		$this->setViewState('MinSelection',$value,log(0));
	}
	
	/**
	 * Get the maximum number of selections required.
	 * @return int max number of selections 
	 */
	function getMaxSelection()
	{
		return $this->getViewState('MaxSelection',-log(0));
	}
	
	/**
	 * Set the maximum number of selections.
	 * @param int max number of selections.
	 */	
	function setMaxSelection($value)
	{
		$this->setViewState('MaxSelection',$value,-log(0));
	}

	/**
	 * Get a comma separated list of required selected values.
	 * @return string comma separated list of required values. 
	 */
	function getRequiredSelections()
	{
		return $this->getViewState('RequiredSelections','');
	}
		
	/**
	 * Set the list of required values, using aa comma separated list.
	 * @param string comma separated list of required values. 
	 */
	function setRequiredSelections($value)
	{
		$this->setViewState('RequiredSelections',$value,'');
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
			if($control instanceof TListControl)
			{
				$count = 0;
				$items = $control->getItems();
				$values = array();
				$required = array();
				$string = $this->getRequiredSelections();
				if(!empty($string))
					$required = preg_split('/,\s*/', $string);
				
				//get the data
				foreach($items as $item)
				{
					if($item->isSelected()) 
					{
						$count++;
						$values[] = $item->getValue();
					}
				}
				
				$exists = true;

				//if required, check the values
				if(!empty($required))
				{
					if(count($values) < count($required) ) return false;
					foreach($required as $require)
						$exists = $exists && in_array($require, $values);
				}
				return $exists && $count >= $this->getMinSelection() 
						&& $count <= $this->getMaxSelection();				
			}
			else 
			{
				throw new TObjectTypeException('TListControl', 
							get_class($control));
			}
		}
		else
			return true;
	}	
	/**
	 * Returns the attributes to be rendered.
	 * This method overrides the parent's implementation.
	 * @return ArrayObject attributes to be rendered
	 */
	protected function getJsOptions()
	{
		$options=parent::getJsOptions();

		$min = $this->getMinSelection();
		$max = $this->getMaxSelection();
		if($min != -INF)
			$options['min']= $min;
		if($max != INF)
			$options['max']= $max;
		$required = $this->getRequiredSelections();
		if(strlen($required))
			$options['required']= $required;
		$id = $options['controltovalidate'].'[]';
		$options['selector'] = $id;			

		return $options;
	}	
}
?>