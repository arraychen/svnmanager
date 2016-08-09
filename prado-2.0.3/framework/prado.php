<?php
/**
 * PRADO bootstrap file.
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
 * @version $Revision: 1.22 $  $Date: 2005/08/19 17:23:24 $
 * @package System
 */

/**
 * the framework installation path
 */
if(!defined('PRADO_DIR')) define('PRADO_DIR',dirname(__FILE__));

/**
 * the class file extension
 */
if(!defined('PRADO_EXT_CLASS')) define('PRADO_EXT_CLASS','.php');

/**
 * set up error handler to catch user errors
 */
set_error_handler("pradoErrorHandler");

/**
 * utility functions file
 */
require_once(PRADO_DIR.'/utils.php');
/**
 * classes file
 */
require_once(PRADO_DIR.'/classes.php');
/**
 * TApplication class file
 */
require_once(PRADO_DIR.'/TApplication.php');

/**
 * @return string prado version
 */
function pradoVersion()
{
	return '2.0.2';
}

/**
 * error handler.
 * This method is invoked by PHP when an error happens.
 * It displays the error if the application state is in debug;
 * otherwise, it saves the error in log.
 */
function pradoErrorHandler($errno, $errstr, $errfile, $errline) 
{
	$errRpt=error_reporting();
	if(($errno & $errRpt)!=$errno)
		return;
	$msg="[$errno] $errstr (@line $errline in file $errfile).";
	pradoFatalError($msg);
}

/**
 * Fatal error handler.
 * This method is used in places where exceptions usually cannot be raised
 * (e.g. magic methods).
 * It displays the debug backtrace if the application state is in debug;
 * otherwise, only error message is displayed.
 * @param string error message
 */
function pradoFatalError($msg)
{
	echo '<h1>Fatal Error</h1>';
	echo '<p>'.$msg.'</p>';
	if(!function_exists('debug_backtrace')) 
		return; 
	$app=pradoGetApplication();
	if(is_null($app) || $app->getApplicationState()==TApplication::STATE_DEBUG)
	{
		echo '<h2>Debug Backtrace</h2>';
		echo '<pre>';
		$index=-1;
		foreach(debug_backtrace() as $t) 
		{
			$index++;
			if($index==0)  // hide the backtrace of this function
				continue;
			echo '#'.$index.' ';
			if(isset($t['file']))
				echo basename($t['file']) . ':' . $t['line']; 
			else 
			   echo '<PHP inner-code>'; 
			echo ' -- '; 
			if(isset($t['class']))
				echo $t['class'] . $t['type']; 
			echo $t['function']; 
			if(isset($t['args']) && sizeof($t['args']) > 0) 
				echo '(...)'; 
			else 
				echo '()'; 
			echo "\n";
		}
		echo '</pre>';
	}
	else
	{
		error_log($msg);
		echo '<h1>Internal Error</h1>';
	}
	exit(1);
}

/**
 * namespaces that are currently used
 */
$pradoNamespaces=array();

/**
 * Includes a class file upon an unknown class
 * This function is automatically called by PHP engine upon an unknown class.
 * It is required that each PRADO class should be defined in a file
 * whose name is the class name.
 * @param string class name
 */
if (!function_exists("__autoload")) {
	function __autoload($className)
	{
		pradoImportClass($className);
	}
}

/**
 * Includes a class definition file.
 * The class definition file is located by searching the used namespaces.
 * If the class definition exists or is imported successfully,
 * the function returns true, otherwise false.
 * @param string class name
 * @return boolean whether the class definition is imported
 */
function pradoImportClass($className)
{
	global $pradoNamespaces;
	if(class_exists($className,false))
		return true;
	foreach($pradoNamespaces as $path)
	{
		if(is_dir($path) && is_file($path.'/'.$className.PRADO_EXT_CLASS))
		{
			require_once($path.'/'.$className.PRADO_EXT_CLASS);
			return class_exists($className,false);
		}
	}
	return false;
}

/**
 * Adds an include search path.
 *
 * A namespace is a dot-connected paths. The first segment of the string
 * refers to a path alias that is defined in the application specification.
 * The rest segments represent the subdirectories in order.
 * For example, 'System.Web.UI' refers to the 'Web/UI' directory under the
 * framework directory. 
 *
 * If the namespace represents a path, it will be inserted
 * at the front of the current include search path.
 *
 * If the namespace represents a file (without the extension), 
 * it will be included (require_once) at the position of calling this function.
 *
 * Do not call this function before the application singleton is created.
 *
 * @param string the namespace string
 */
function using($namespace)
{
	global $pradoNamespaces;
	if(isset($pradoNamespaces[$namespace]))
		return;
	$path=pradoGetApplication()->translatePathAlias($namespace);
	if(is_null($path))
		throw new TPathAliasNotDefinedException($namespace);
	else
	{
		if(is_dir($path))
			$pradoNamespaces[$namespace]=$path;
		else if(is_file($path.PRADO_EXT_CLASS))
		{
			$pradoNamespaces[$namespace]=$path.PRADO_EXT_CLASS;
			require_once($path.PRADO_EXT_CLASS);
		}
		else
			throw new TNamespaceInvalidException($namespace);
	}
}

/**
 * Returns the application singleton.
 *
 * In the first invocation of the method, it will construct an application instance
 * by either loading from a previously serialized instance from $cacheFile
 * or creating a new one based on the specification file $specFile.
 *
 * In the following invocations, the parameters can be omitted and the singleton is returned.
 *
 * @param string path of the application specification file (either absolute or relative to current requesting script)
 * @param string path of the cache file that stores serialized TApplication instance
 * @param string the application class name, TApplication by default.
 * @return TApplication the application singleton
 */
function pradoGetApplication($specFile='',$cacheFile='',$className='TApplication')
{
	static $application=null;
	if(!strlen($specFile))
		return $application;
	if(strlen($cacheFile) && is_file($cacheFile))
		$application=pradoUnserializeObject(file_get_contents($cacheFile));
	else if(strlen($specFile))
	{
		$application=new $className($specFile);
		if(!($application instanceof TApplication))
			throw new TApplicationInheritanceException($className);
		// serialize the application instance and save it to cache
		if(strlen($cacheFile) && ($fp=fopen($cacheFile,"wb")))
		{
			fputs($fp,pradoSerializeObject($application));
			fclose($fp);
		}
	}
	using('System');
	using('System.Web.UI');
	using('System.Exception');
	return $application;
}

?>