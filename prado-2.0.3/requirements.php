<?php
/**
 * PRADO Requirements Checker script
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Pim van der Zwet. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Pim van der Zwet
 * @version $Revision: 1.3 $  $Date: 2005/06/18 12:27:06 $
 * @package prado
 */

/**
 * PRADO Requirements Checker script
 *
 * This script will check if your system meets the requirements
 * for running PRADO. It will check if you are running the right
 * version of PHP, if you included the right libraries and if your
 * php.ini file settings are correct. 
 *
 */

/**
 * application name printed on top of the page
 */ 
define("APPLICATION_NAME", "PRADO");

/**
 * row width to use
 */ 
define("ROW_WIDTH", 60);

/**
 * some application configuration settings
 */
define("BLOCKING", "BLOCKING"); 
define("MUSTHAVE", "MUSTHAVE");
define("SHOULDHAVE", "SHOULDHAVE");
define("OK", "ok");
define("FAILED", "failed");



/** list of software requirements **/

$reqs['phpversion > 3'] = array("impact"=>BLOCKING, "evaluate"=>"preg_match('/^[3-9].[0-9].[0-9].*$/', phpversion());", "tip"=>"");
$reqs['phpversion > 5'] = array("impact"=>MUSTHAVE, "evaluate"=>"preg_match('/^5.*$/', phpversion());", "tip"=>"");

/** libs **/
$reqs['SPL Module exists'] = array("impact"=>MUSTHAVE, "evaluate"=>"check_phpmodule('spl');", "tip"=>"");
$reqs['SimpleXML Module exists'] = array("impact"=>MUSTHAVE, "evaluate"=>"check_phpmodule('simplexml');", "tip"=>"");
//$reqs['XML Module exists'] = array("impact"=>MUSTHAVE, "evaluate"=>"check_phpmodule('xml');", "tip"=>"");
//$reqs['Tokenizer Module exists'] = array("impact"=>SHOULDHAVE, "evaluate"=>"check_phpmodule('tokenizer');", "tip"=>"");
//$reqs['MySQL Module exists'] = array("impact"=>MUSTHAVE, "evaluate"=>"check_phpmodule('mysql');", "tip"=>"");
//$reqs['CType Module exists'] = array("impact"=>MUSTHAVE, "evaluate"=>"preg_match('/1/', check_phpmodule('ctype'));", "tip"=>"");

/** ini settings **/
//$reqs['PHP Safe mode on'] = array("impact"=>MUSTHAVE, "evaluate"=>"preg_match('//', ini_get('safe_mode'));", "tip"=>"");
//$reqs['Clone Objects check'] = array("impact"=>MUSTHAVE, "evaluate"=>"preg_match('/Off/', ini_get('zend.ze1_compatibility_mode'));", "tip"=>"");
$reqs['Clone Objects check'] = array("impact"=>MUSTHAVE, "evaluate"=>"!ini_get('zend.ze1_compatibility_mode');", "tip"=>"set zend.ze1_compatibility_mode to Off");
$reqs['Magic quotes check'] = array("impact"=>MUSTHAVE, "evaluate"=>"!ini_get('magic_quotes_runtime');", "tip"=>"set magic_quotes_runtime to Off");

?>
<html>
<head></head>
<body>
<pre>

<?=APPLICATION_NAME ?> REQUIREMENTS CHECKER $Revision: 1.3 $  $Date: 2005/06/18 12:27:06 $
--------------------------------------------------------------

<?php
show_results(check_requirements($reqs));
?>

--------------------------------------------------------------
* Fix all the <b>blocking</b> requirements.
* Fix all the <b>musthave</b> requirements.
* Try to fix all the <b>shouldhave</b> requirements.

Finished!
</pre>
</body>
</html>
<?php

/** Base functions **/

/**
 * Checks requirements by evaluating an expression. If the result is true,
 * the requirement is met. The result of the evaluation is remembered. 
 * Blocking requirements prevent the process to continue any further.
 *
 * Each requirement has a unique key, an impact indication, an expression
 * to evaluate and a tip that will explain how to fix this requirement.
 * The key will be displayed by show_results() along with the result and
 * if it failed an impact indication.
 * 
 * Some tips on patterns:
 * To check if a function returns <i>false</i>, use '//'.
 * To check if a function returns <i>true</i>, use '/1/'.
 * To check for either, use '/|1/'.
 *
 * @param array requirements
 * @return array requirements
 */
function check_requirements($reqs)
{
	/** process all requirements **/
	while(list($key, $req) = each($reqs))
	{	
		/** evaluate the rule **/
		eval("\$r = " . $req['evaluate']);
		if($r)
			$reqs[$key]['result'] = OK;
		else
		{
			$reqs[$key]['result'] = FAILED;
			if($req['impact'] == BLOCKING)
				break;
		}
	}
	return $reqs;
}

/**
 * Prints every requirement from an array of requirements and it's 
 * status to the screen. If a requirement was not met, extra information
 * about it's impact is printed to the screen. Blocking requirements
 * stop the printing process.
 * @param array requirements
 * @return void
 */
function show_results($reqs)
{
	print "<pre>";
	while(list($key, $req) = each($reqs))
	{
		$status = $req['result'];
		print "* " . str_pad($key, ROW_WIDTH-14, ".", STR_PAD_RIGHT);
		print str_pad("[<b>$status</b>]", 21, ".", STR_PAD_LEFT);
		if($status == FAILED)
		{
			print "\n";
			print str_pad("<i>This is a " . $req['impact'] . " requirement!</i>", ROW_WIDTH, " ", STR_PAD_LEFT);
			if(strlen($req['tip']))
				print '&nbsp;Hint: '.$req['tip'];

		}
	print "<pre>";		
	}

}


/** helper functions **/

/**
 * Checks if a certain php module or extension is loaded / available.
 * @param string module name
 */
function check_phpmodule($name)
{
	return extension_loaded($name);
}

?>