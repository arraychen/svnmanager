<?php
/**
 * TAutoComplete class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */

/**
 * TAutoComplete class, a textbox with "auto-suggest" feature.
 *
 * Standard textbox, both single and multi lines, with auto-suggest feature.
 * Keystrokes within the textbox are buffered util a delay in the typing when
 * it send a callback request to ask for additional suggestion data.
 *
 * The upon receiving new suggestions, the user may select, using keyboard or mouse,
 * a guestion or continue typing. If suggestion is selected, the suggestion is replace
 * with the partially typed word.
 *
 * The display of the suggestions may be customerized using the ItemTemplate property.
 *
 * Namespace: System.Web.UI.ActiveControls
 *
 * Properties
 * - <b>ResultCssClass</b>, string
 *   <br />Sets or Gets the Css classname for the suggestion results panel.
 * - <b>Tokens</b>, array
 *   <br />Sets or Gets the tokens to separate the typing buffer to instantiate
 *    a new suggestion request, e.g. with "," token, words after a comma will be 
 *    treated as new possible partial-words to begin suggestions.
 * - <b>ItemTemplate</b>, string
 *   <br />Sets or Gets the template to customize each suggestion option.
 *
 * Events
 * - <b>OnItemCommand</b> Occurs at each suggestion option creation.
 *
 * Example
 * <code>
 * <com:TAutoComplete 
 *		OnTextChanged="filterAutoComplete"
 *		ResultCssClass="autocomplete"  />
 * </code>
 *
 * <code>
 * class AutoCompleteTest extends TCallbackPage 
 * {
 *	
 *	function filterAutoComplete($sender, $param)
 *	{
 *		if($this->IsCallback)
 *		{
 *			$result = $sender->renderResult(array('hello', 'world'));
 *			$this->CallbackResponse->data = $result;
 *		}
 *	}
 *}
 *</code>
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI.ActiveControls
 */
class TAutoComplete extends TTextBox implements ICallbackEventHandler 
{
	const RESULT_PANEL_ID = 'results';
	
	protected $resultCssClass;
	protected $tokens = array();
	protected $itemTemplate;

	public function getResultCssClass()
	{
		return $this->resultCssClass;
	}
	
	public function setResultCssClass($value)
	{
		$this->resultCssClass = $value;
	}

	public function getTokens()
	{
		return $this->tokens;
	}
	
	public function setTokens($value)
	{
		$this->tokens = $value;
	}

	public function getItemTemplate()
	{
		return $this->itemTemplate;
	}
	
	public function setItemTemplate($value)
	{
		$this->itemTemplate = $value;
	}	
		
	function render()
	{
		$this->Page->registerClientScript('controls');
		$this->renderJs();
		$contents = parent::render();
		$panel = $this->createComponent('TPanel', self::RESULT_PANEL_ID);
		$panel->setCssClass($this->getResultCssClass());
		$contents .= $panel->render();
		return $contents;
	}
	
	function raiseCallbackEvent($params)
	{
		$this->raisePostDataChangedEvent();
	}
	
	public function renderResult($data=array())
	{
		$template = $this->getItemTemplate();
		
		if(!is_array($data)) return '';
		$result = '<ul>';
		foreach($data as $value)
		{
			if(strlen($template) > 0)
				$value = $this->renderItemTemplate($template, $value);
			
			$result .= '<li>'.$value.'</li>';
		}
		return $result . '</ul>';
	}
	
	protected function renderItemTemplate($template, $value)
	{
		$item = $this->createComponent('TAutoCompleteItem');	
		$item->instantiateTemplate($template);	
		$item->Data = $value;
		$item->dataBind();
		
		$p = new TAutoCompleteItemEventParameter;
		$p->item = $item;
		$this->onItemCommand($p);
		
		return trim($item->render());	
	}
	
	protected function onItemCommand($param)
	{
		$this->raiseEvent('OnItemCommand',$this,$param);
	}	
	
	protected function renderJs()
	{
		$ID = $this->ClientID;
		$result = self::RESULT_PANEL_ID;
		$option['tokens'] = $this->renderTokens();
		$options = TJavascript::toList($option);//$this->renderTokens();	
		$js = "new Prado.AutoCompleter('{$ID}', '{$ID}:{$result}', {$options});";
		$this->Page->registerEndScript($ID,$js);		
	}
	
	protected function renderTokens()
	{
		$array = $this->getTokens();
		if(count($array) <= 0) return false;
		$tokens = array();
		foreach($array as $value)
			$tokens[] = "'{$value}'";
		return '['.implode($tokens,",").']';
	}
}

class TAutoCompleteItem extends TControl 
{
	public $Data;
}

class TAutoCompleteItemEventParameter extends TEventParameter
{
	public $item=null;
}

?>