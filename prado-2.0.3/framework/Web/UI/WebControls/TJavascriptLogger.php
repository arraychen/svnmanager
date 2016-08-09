<?php
/**
 * TJavascriptLogger component class file.
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
 * @version $Revision: 1.2 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.UI.WebControls
 */

/**
 * TJavascriptLogger class.
 *
 * Provides logging for client-side javascript. Example: template code
 * <code><com:TJavascriptLogger /></code>
 *
 * Client-side javascript code to log info, error, warn, debug
 * <code>Logger.warn('A warning');
 * Logger.info('something happend');
 * </code>
 *
 * To see the logger and console, press ALT-D (or CTRL-D on OS X).
 * More information on the logger can be found at
 * http://gleepglop.com/javascripts/logger/
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.2 $  $Date: 2005/11/06 23:02:33 $
 * @package System.Web.UI.WebControls
 */
class TJavascriptLogger extends TPanel
{
	/**
	 * Register the required javascript libraries.
	 */
	function onPreRender($param)
	{
		parent::onPreRender($param);
		$this->Page->registerClientScript('logger');
		$this->renderMessage();
	}
	
	/**
	 * Display some general usage information.
	 */
	protected function renderMessage()
	{
		$info = '(<a href="http://gleepglop.com/javascripts/logger/" target="_blank">more info</a>).';
		$usage = 'Press ALT-D (Or CTRL-D on OS X) to toggle the javascript log console';
		$this->addBody("{$usage} {$info}");
	}
}

?>