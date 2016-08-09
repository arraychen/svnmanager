<?php
/**
 * TRequestOptions class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:weizhuo[at]gmail[dot]com Wei Zhuo}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */

/**
 * TRequestOptions class. Event options for AJAX requests.
 *
 * For example, to show the "indicator" element on the following
 *  Drop container request
 * <code>
 * <com:TDropContainer ... RequestOptions="Option1" />
 * <com:TRequestOptions ID="Option1" Loading="Element.show('indicator')" />
 * </code>
 *
 * Properties
 * - <b>Uninitialized</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request is uninitialized.
 * - <b>Loading</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request is initiated
 * - <b>Loaded</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request begins.
 * - <b>Interactive</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request is in progress.
 * - <b>Complete</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request returns.
 * - <b>Success</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request returns and is successful.
 * - <b>Failure</b>, string, in viewstate
 *	 <br />javascript code to execute when AJAX request returns and fai.
 *
 * Namespace: System.Web.UI.ActiveControls
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */
class TRequestOptions extends TControl
{
		
	public function setLoading($value)
	{
		$this->setViewState('Loading', $value, '');
	}
	
	public function getLoading()
	{
		return $this->getViewState('Loading', '');
	}
	
	public function setComplete($value)
	{
		$this->setViewState('Complete', $value, '');
	}
	
	public function getComplete()
	{
		return $this->getViewState('Complete', '');
	}
		
	public function setLoaded($value)
	{
		$this->setViewState('Loaded', $value, '');
	}
	
	public function getLoaded()
	{
		return $this->getViewState('Loaded', '');
	}
	
	public function setUninitialized($value)
	{
		$this->setViewState('Uninitialized', $value, '');
	}
	
	public function getUninitialized()
	{
		return $this->getViewState('Uninitialized', '');
	}
		
	public function setFailure($value)
	{
		$this->setViewState('Failure', $value, '');
	}
	
	public function getFailure()
	{
		return $this->getViewState('Failure', '');
	}
	
	public function setInteractive($value)
	{
		$this->setViewState('Interactive', $value, '');
	}
	
	public function getInteractive()
	{
		return $this->getViewState('Interactive', '');
	}
	
	public function setSuccess($value)
	{
		$this->setViewState('Success', $value, '');
	}
	
	public function getSuccess()
	{
		return $this->getViewState('Success', '');
	}
	
	/**
	 * Gets the AJAX request options.
	 * @return string request options
	 */
	public function getOptions()
	{
		$options['onLoading'] = $this->getLoading();
		$options['onComplete'] = $this->getComplete();
		$options['onLoaded'] = $this->getLoaded();
		$options['onUninitialized'] = $this->getUninitialized();
		$options['onFailure'] = $this->getFailure();
		$options['onInteractive'] = $this->getInteractive();
		$options['onSuccess'] = $this->getSuccess();
		return $this->toFunctionList($options);
	}
	
	/**
	 * Build request option javascript functions.
	 */
	protected function toFunctionList($functions)
	{
		$results = array();
		foreach($functions as $k => $v)
			if(!empty($v))
				$results[] = "'{$k}':function(request,transport,data){ {$v} }\n";
		return implode(', ',$results);
	}
}

?>