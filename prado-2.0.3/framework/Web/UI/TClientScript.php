<?php
/**
 * TClientScript class file.
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
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.UI
 */

/**
 * PradoClientScript class.
 *
 * PradoClientScript::register($control, $scripts) registers the required
 * client-side javascript files to the page. Prado provides the following
 * basic javascript libraries, e.g.<b>$this->registerClientScript('dom')</b>
 *
 * - <b>base</b> basic javascript utilities, e.g. $()
 * - <b>dom</b> DOM and Form functions, e.g. $F(inputID) to retrive form input values.
 * - <b>effects</b> Effects such as fade, shake, move
 * - <b>controls</b> Prado client-side components, e.g. Slider, AJAX components
 * - <b>validator</b> Prado client-side validators.
 * - <b>ajax</b> Prado AJAX library including Prototype's AJAX and JSON.
 * 
 * Dependencies for each library are automatically resolved.
 *
 * Namespace: System.Web.UI
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.1 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.UI
 */
class TClientScript
{
	/**
	 * Client-side javascript library dependencies
	 * @var array
	 */
	protected static $dependencies = array(
		'base' => array('base'), 
		'dom' => array('base', 'dom'),
		'effects' => array('base', 'dom', 'effects'),
		'controls' => array('base', 'dom', 'effects', 'controls'),
		'validator' => array('base', 'dom', 'validator'),
		'logger' => array('base', 'dom', 'logger'),
		'ajax' => array('base', 'dom', 'ajax')
		);

	/**
	 * Client service URI.
	 * @var array
	 */
	protected static $services = array(
		'ajax' => 'AJAX' //service name
		);	
		
	/**
	 * Register client-side libraries for adding to the page.
	 * The parameter $scripts can be an array, or a string.
	 * E.g. PradoClientScript::register($this, array('dom', 'mycontrols'));
	 * to register the DOM library and its dependencies, and to register
	 * a custom javascript file 'mycontrols.js' (which must be web accessiable
	 *  in the /js/ directoy.
	 * @param TControl the control for registering the scripts
	 * @param string the library to register.
	 */
	public static function register($control, $scripts)
	{
		$files = self::getDependencies($scripts);
		$path = $control->Application->getResourceLocator()->getJsPath().'/';
		foreach($files as $file)
		{
			if($control->Page->isScriptFileRegistered($file) == false)
				$control->Page->registerScriptFile($file, $path.$file.'.js');
			if(isset(self::$services[$file]))
			{
				$service = self::getServiceUri($control,self::$services[$file]);
				if(!empty($service)) $control->Page->registerScriptFile($service, $service);				
			}
		}
	}
	
	/**
	 * Resolve dependencies for the given library.
	 * @param array list of libraries to load.
	 * @return array list of libraries including its dependencies.
	 */
	protected static function getDependencies($scripts)
	{
		$files = array();
		if(!is_array($scripts)) $scripts = array($scripts);
		foreach($scripts as $script)
		{
			if(isset(self::$dependencies[$script]))
				$files = array_merge($files, self::$dependencies[$script]);
			$files[] = $script;
		}
		$files = array_unique($files);
		return $files;
	}
	
	/**
	 * Get service URI.
	 * @param TControl service control.
	 * @param string service name.
	 * @return string service URI.
	 */
	protected static function getServiceUri($control, $service)
	{
		$app = $control->ServiceManager->getServices($service);
		if(is_array($app) && count($app) > 0)
			return $app[0]->getClientUri();
	}
}

?>