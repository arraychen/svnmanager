<?php

//Build script for compressing javascript files.
//Requires java 1.4+

$base_dir = dirname(__FILE__).'/';
$library = './';

//output directories
$outputs[] = '../js/';
$outputs[] = '../../examples/js/';
$outputs[] = '../../tests/js/';
$outputs[] = '../../../petshop/js/';

/* javascript library files */

/*
//base javascript functions
$lib_files['base.js'][] = 'prototype/prototype.js';
$lib_files['base.js'][] = 'prototype/compat.js';
$lib_files['base.js'][] = 'prototype/base.js';
$lib_files['base.js'][] = 'extended/base.js';
$lib_files['base.js'][] = 'extended/util.js';
$lib_files['base.js'][] = 'prototype/string.js';
$lib_files['base.js'][] = 'extended/string.js';
$lib_files['base.js'][] = 'prototype/enumerable.js';
$lib_files['base.js'][] = 'prototype/array.js';
$lib_files['base.js'][] = 'extended/array.js';
$lib_files['base.js'][] = 'prototype/hash.js';
$lib_files['base.js'][] = 'prototype/range.js';
$lib_files['base.js'][] = 'extended/functional.js';
$lib_files['base.js'][] = 'prado/prado.js';

//dom functions
$lib_files['dom.js'][] = 'prototype/dom.js';
$lib_files['dom.js'][] = 'extended/dom.js';
$lib_files['dom.js'][] = 'prototype/form.js';
$lib_files['dom.js'][] = 'prototype/event.js';
$lib_files['dom.js'][] = 'extended/event.js';
$lib_files['dom.js'][] = 'prototype/position.js';
$lib_files['dom.js'][] = 'extra/getElementsBySelector.js';
$lib_files['dom.js'][] = 'extra/behaviour.js';
$lib_files['dom.js'][] = 'effects/util.js';

//effects
$lib_files['effects.js'][] = 'effects/effects.js';

*/
//controls
$lib_files['controls.js'][] = 'effects/controls.js';
$lib_files['controls.js'][] = 'effects/dragdrop.js';
$lib_files['controls.js'][] = 'prado/controls.js';

//logging
//$lib_files['logger.js'][] = 'extra/logger.js';

//$lib_files['ajax.js'][] = 'prototype/ajax.js';
//$lib_files['ajax.js'][] = 'prado/ajax.js';
//$lib_files['ajax.js'][] = 'prado/json.js';

/*
//rico
$lib_files['rico.js'][] = 'effects/rico.js';

//javascript templating
$lib_files['template.js'][] = 'extra/tp_template.js';

//validator
$lib_files['validator.js'][] = 'prado/validation.js';
$lib_files['validator.js'][] = 'prado/validators.js';

//date picker
$lib_files['datepicker.js'][] = 'prado/datepicker.js';
*/

/*============ Build the javascript files =========*/

foreach($lib_files as $output_file => $lib)
{
	$files = get_library_files($library, $lib);
	$compressed = get_compressed_content($files);
	save_contents($outputs, $output_file, $compressed);
}





/*============ utility functions ==============*/

function save_contents($outputs, $output_file, $contents)
{
	$tmp_file = $output_file.'.tmp';
	file_put_contents($tmp_file, $contents);
	copy_files($outputs, $tmp_file, $output_file);
	echo "Saving to <b>{$output_file}.</b></br></br>\n\n";
	unlink($tmp_file);
}

function get_library_files($lib_dir, $lib_files)
{
	$files = array();
	foreach($lib_files as $file)
	{
		if(is_file($lib_dir.$file))
			$files[] = $lib_dir.$file;
		else
			echo '<b>File not found '.$lib_dir.$file.'</b>';
	}
	return $files;
}

function get_compressed_content($files)
{
	$contents = '';
	foreach($files as $file)
	{
		$tmp_file = $file.'.tmp';
		rhino_compress($file, $tmp_file);
		$contents  .= file_get_contents($tmp_file);
		unlink($tmp_file);
	}
	return $contents;
}


function rhino_compress($input, $output)
{
	$command = 'java -jar custom_rhino.jar -c INPUT > OUTPUT';
	$find = array('INPUT', 'OUTPUT');
	$replace =  array($input, $output);
	$command = str_replace($find, $replace, $command);
	echo "Compressing {$input} <br/>\n".
	system($command);
}


function copy_files($outputs, $source, $filename)
{
	foreach($outputs as $dir)
		copy($source, $dir.$filename);
}

?>