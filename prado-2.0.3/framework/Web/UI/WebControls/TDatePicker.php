<?php
/**
 * TDatePicker class file
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
 * @version $Revision: 1.12 $  $Date: 2005/07/13 21:44:50 $
 * @package System.Web.UI.WebControls
 */

/**
 * TTextBox class file
 */
require_once(dirname(__FILE__).'/TTextBox.php');

/**
 * TDatePicker class
 *
 * TDatePicker wraps the DHTML Calendar javascript developed by dynarch.com
 * {@link http://www.dynarch.com/projects/calendar/}.
 *
 * TDatePicker displays a text box for date(time) input purpose.
 * When the text box receives focus, a calendar will pop up and users can
 * pick up from it a date(time) that will be automatically entered into the text box.
 * By default, the calendar will only show dates. If the <b>ShowTime</b> property
 * is set to true, the calendar will also show time that can be selected by users.
 * The format of the date string displayed in the text box is determined by
 * the <b>DateFormat</b> property. Valid formats are the combination of the following tokens,
 *
 * <b>WARNING: Languages other than EN may not work.</b>
 *
 * <code>
 *   %a  abbreviated weekday name  
 *   %A  full weekday name  
 *   %b  abbreviated month name  
 *   %B  full month name  
 *   %C  century number  
 *   %d  the day of the month ( 00 .. 31 )  
 *   %e  the day of the month ( 0 .. 31 )  
 *   %H  hour ( 00 .. 23 )  
 *   %I  hour ( 01 .. 12 )  
 *   %j  day of the year ( 000 .. 366 )  
 *   %k  hour ( 0 .. 23 )  
 *   %l  hour ( 1 .. 12 )  
 *   %m  month ( 01 .. 12 )  
 *   %M  minute ( 00 .. 59 )  
 *   %n  a newline character  
 *   %p  ¡°PM¡± or ¡°AM¡±  
 *   %P  ¡°pm¡± or ¡°am¡±  
 *   %S  second ( 00 .. 59 )  
 *   %s  number of seconds since Epoch (since Jan 01 1970 00:00:00 UTC)  
 *   %t  a tab character  
 *   %U, %W, %V  the week number 
 *   %u  the day of the week ( 1 .. 7, 1 = MON ) 
 *   %w  the day of the week ( 0 .. 6, 0 = SUN ) 
 *   %y  year without the century ( 00 .. 99 ) 
 *   %Y  year including the century ( ex. 1979 ) 
 *   %%  a literal % character  
 * </code>
 * For example, the format "%m/%d/%y" will show the date Dec. 31, 2004 as "12/31/04".
 * In case you do not want to show calendar at all, set the <b>ShowCalendar</b> property to false.
 *
 * Note, you can set the <b>ReadOnly</b> property to true so that users cannot
 * update the content in the textbox directly. The content can still be changed
 * by picking up date from the calendar, though.
 * Set <b>Enabled</b> to false to totally forbid editting the text box.
 *
 * Note, to use this component, you have to copy the directory
 * "<framework>/js/datepicker" to the "js" directory which should be under
 * the directory containing the entry script file.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>DateFormat</b>, string, default="%m/%d/%Y", kept in viewstate
 *   <br>Gets or sets the format that the date should be displayed
 * - <b>ShowCalendar</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets whether the calendar window should pop up when the control receives focus.
 * - <b>ShowTime</b>, boolean, default=false, kept in viewstate
 *   <br>Gets or sets whether the calendar window will allow selection of time.
 *
 * Compatibility
 * - The calendar javascript is supported by Internet Explorer 5.0+ for Windows,
 * Mozilla, Netscape 7.x, Mozilla FireFox (any platform),
 * Other Gecko-based browsers (any platform)
 * Konqueror 3.2+ for Linux and Apple Safari for Macintosh
 * Opera 7+ (any platform) 
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class TDatePicker extends TTextBox
{
	/**
	 * URL (either relative or absolute) of javascript file that contains functions for visual editting
	 */
	const JS_DATEPICKER='datepicker';

	/**
	 * language js file to locale mappings.
	 * @var array
	 */
	protected $langMap = array(
		//script-suffix => array(locale possiblities),
		'af' => array('af'),
		'br' => array('pt_BR'),
		'ca' => array('ca'),
		'cs-utf8' => array('cs'),
		'da' => array('da'),
		'de' => array('de'),
		'du' => array(), //DU?
		'el' => array('el'),
		'en' => array('en'),
		'es' => array('es'),
		'fi' => array('fi'),
		'fr' => array('fr'),
		'hr-utf8' => array('hr'),
		'hu' => array('hu'),
		'it' => array('it'),
		'jp' => array('ja'),
		'ko-utf8' => array('ko'),
		'lt-utf8' => array('lt'),
		'nl' => array('nl'),
		'no' => array('no'),
		'pl-utf8' => array('pl'),
		'pt' => array('pt'),
		'ro' => array('ro'),
		'ru' => array('ru'),
		'si' => array('sl'), //Slovenian?
		'sk' => array(), //What is SK?
		'sv' => array('sv'),
		'tr' => array('tr'),
		'zh' => array('zh'),
		//new to 1.0
		'al' => array(), //AL?
		'cn-utf8' => array('zh_CN'),
		'big5-utf8' => array('zh_TW', 'zh_HK'),
		'he-utf8' => array('he'),
		'lv' => array('lv')
			);

	/**
	 * Overrides parent implementation to disable body addition.
	 * @param mixed the object to be added
	 * @return boolean
	 */
	public function allowBody($object)
	{
		return false;
	}

	/**
	 * @return string the format of the date string
	 */
	public function getDateFormat()
	{
		return $this->getViewState('DateFormat','%m/%d/%Y');
	}

	/**
	 * Sets the format of the date string.
	 * @param string the format of the date string
	 */
	public function setDateFormat($value)
	{
		$this->setViewState('DateFormat',$value,'%m/%d/%Y');
	}

	/**
	 * @return boolean whether the calendar window should pop up when the control receives focus
	 */
	public function isShowCalendar()
	{
		return $this->getViewState('ShowCalendar',true);
	}

	/**
	 * Sets whether to pop up the calendar window when the control receives focus
	 * @param boolean whether to show the calendar window
	 */
	public function setShowCalendar($value)
	{
		$this->setViewState('ShowCalendar',$value,true);
	}

	/**
	 * @return boolean whether the calendar window should show time
	 */
	public function isShowTime()
	{
		return $this->getViewState('ShowTime',false);
	}

	/**
	 * Sets whether to show time on the calendar window
	 * @param boolean whether to show time on the calendar window
	 */
	public function setShowTime($value)
	{
		$this->setViewState('ShowTime',$value,false);
	}


	/**
	 * Gets the current culture.
	 * @return string current culture, e.g. en_AU.
	 */
	public function getCulture()
	{
		return $this->getViewState('Culture', '');
	}

	/**
	 * Sets the culture/language for the date picker.
	 * @param string a culture string, e.g. en_AU.
	 */
	public function setCulture($value)
	{
		$this->setViewState('Culture', $value, '');
	}

	/**
	* Registers the script and style files
	* @return void
	*/
	public function onPreRender($param)
	{
		if ($this->isShowCalendar())
		{
   			$page=$this->getPage();
			if(!$page->isScriptFileRegistered('TDatePicker1'))
			{
    			$scriptPath=$this->Application->getResourceLocator()->getJsPath().'/'.self::JS_DATEPICKER;
    			$lang = $this->getLanguageSuffix($this->getCulture());
    			$page->registerStyleFile('TDatePicker',$scriptPath.'/css/calendar-system.css');
    			$page->registerScriptFile('TDatePicker1',$scriptPath.'/calendar.js');
    			$page->registerScriptFile('TDatePicker2',$scriptPath."/lang/calendar-{$lang}.js");
    			$page->registerScriptFile('TDatePicker3',$scriptPath.'/calendar-setup.js');
    			$script="
    if (typeof(Calendar) == \"undefined\")
    	alert(\"Unable to find script library '$scriptPath'. Try placing this directory manually, or redefine constant JS_DATEPICKER in TDatePicker.php.\");
    ";
    			$page->registerEndScript('TDatePicker',$script);
			}
		}
		parent::onPreRender($param);
	}	

	/**
	 * Renders the datepicker
	 * @return string the rendering result
	 */
	public function render()
	{
		if($this->isShowCalendar())
		{
			$id=$this->getClientID();
			$format=$this->getDateFormat();
			$showTime=$this->isShowTime()?'true':'false';
			$script="
Calendar.setup({
	inputField:\"$id\",
	ifFormat:\"$format\",
	showsTime:$showTime,
	timeFormat:\"24\",
	eventName:\"focus\"
});
";
			$this->Page->registerEndScript($id,$script);
		}
		return parent::render();
	}

	/**
	 * Get the language prefix from the mappings.
	 * @param string a culture identifier.
	 * @return string the js language file suffix
	 */
	protected function getLanguageSuffix($culture)
	{
		
		if(empty($culture))
		{
			$app = $this->Application->getGlobalization();
			
			if(!is_null($app))
				$culture = $app->Culture;
		}

		$cultureinfo = preg_split('/(_|-)/', $culture);
		$lang = $cultureinfo[0];
		$variant = null;
		if(count($cultureinfo) > 1)
			$variant = $lang.'_'.$cultureinfo[1];

		//check the variant
		if(!is_null($variant))
		{
			foreach($this->langMap as $suffix => $locales)
			{
				if(in_array($variant, $locales))
						return $suffix;
			}
		}
		foreach($this->langMap as $suffix => $locales)
		{
			if(in_array($lang, $locales))
					return $suffix;
		}
		return 'en';
	}
}

?>