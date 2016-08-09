<?php
/**
 * TEmailAddressValidator class file
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
 * @version $Revision: 1.8 $  $Date: 2005/04/06 18:15:39 $
 * @package System.Web.UI.WebControls
 */

/**
 * TRegularExpressionValidator class file
 */
require_once(dirname(__FILE__).'/TRegularExpressionValidator.php');

/**
 * TEmailAddressValidator class
 *
 * TEmailAddressValidator validates whether the value of an associated
 * input component is a valid email address. It will check MX record
 * if checkdnsrr() is implemented.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TEmailAddressValidator extends TRegularExpressionValidator
{
	const EMAIL_REGEXP="\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*";

	public function getRegularExpression()
	{
		return self::EMAIL_REGEXP;
	}

	public function setRegularExpression($value)
	{
		throw new Exception('RegularExpression is not allowed to be modified.');
	}

	public function evaluateIsValid()
	{
		$valid=parent::evaluateIsValid();
		if($valid && function_exists('checkdnsrr'))
		{
			$idPath=$this->getControlToValidate();
			if(strlen($idPath))
			{
				$control=$this->getTargetControl($idPath);
				$value=$control->getValidationPropertyValue();
				if(strlen($value))
				{
					$pos=strpos($value,'@');
					if($pos===false)
						$valid=false;
					else
					{
						$domain=substr($value,$pos+1);
						$valid=strlen($domain)?checkdnsrr($domain,'MX'):false;
					}
				}
			}
		}
		return $valid;
	}
}

?>