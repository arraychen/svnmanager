<?php
/**
 * Common routines file
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
 * @version $Revision: 1.13 $  $Date: 2005/09/02 02:33:44 $
 * @package System
 */

/**
 * Strips back slashes from a string or an array.
 * @param mixed the data to be processed
 * @return mixed the processed data
 */
function pradoStripSlashes(&$data)
{
	return is_array($data)?array_map('pradoStripSlashes',$data):stripslashes($data);
}

/**
 * Replaces each single quote with two single quotes.
 * This function is mainly used to escape data used for SQL queries.
 * It can process a single string or an array recursively.
 * @param mixed the data to be processed
 * @return mixed the processed data
 */
function pradoEscapeQuotes(&$data)
{
	return is_array($data)?array_map('pradoEscapeQuotes',$data):strtr($data,array("'"=>"''"));
}

/**
 * Encodes a string.
 *
 * The string is encoded by HTML-encoding special characters (&, ", ', <, >).
 * 
 * @param string|array the string or array of strings to be encoded
 * @return string|array the encoded result
 * @see pradoDecodeData
 */
function pradoEncodeData($data)
{
	if(is_array($data))
		return array_map('pradoEncodeData',$data);
	else
		return strtr($data,array('&'=>'&amp;','"'=>'&quot;',"'"=>'&#039;','<'=>'&lt;','>'=>'&gt;'));
}

/**
 * Decodes a string.
 *
 * The string is decoded by HTML-decoding special characters (&, ", ', <, >).
 * 
 * @param string|array the string or array of strings to be encoded
 * @return string|array the encoded result
 * @see pradoEncodeData
 */
function pradoDecodeData($data)
{
	if(is_array($data))
		return array_map('pradoDecodeData',$data);
	else
		return strtr($data,array('&amp;'=>'&','&quot;'=>'"','&#039;'=>"'",'&lt;'=>'<','&gt;'=>'>'));
}

/**
 * Returns a path with respect to a context path.
 * If the path is absolute, it will be returned without change.
 * If the path is relative, the context path will be prefixed to it.
 * @param string the path to be translated
 * @param string the context path
 * @return string the translated path
 */
function pradoGetContextPath($path,$context)
{
	$path=strtr($path,'\\','/');
	return preg_match('/^\\/|.:\\//',$path)?$path:"$context/$path";
}

/**
 * Sends a file to the end-user.
 *
 * This method reads a server file and sends it to the end-user.
 * It must be invoked before any header information is sent out.
 * @param string the absolute or relative (to the current executing script) path of the file to be sent.
 */
function pradoSendFile($fileName, $mimeType="", $remoteFilename="", $inline=false)
{
    static $defaultMimeTypes=array(     'css'=>'text/css',
                                        'gif'=>'image/gif',
                                        'jpg'=>'image/jpeg',
                                        'jpeg'=>'image/jpeg',
                                        'htm'=>'text/html',
                                        'html'=>'text/html',
                                        'js'=>'javascript/js'
                                   );

    if(!is_file($fileName))
        exit();
        header("Pragma: public");
        header("Expires: 0"); // set expiration time
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        if(!$mimeType)
        {
            $mimeType='text/plain';
            if (function_exists ("mime_content_type"))
                $mimeType=mime_content_type($fileName);
        else
        {
            $ext=strtolower(substr(strrchr($fileName,'.'),1));
            if(isset($defaultMimeTypes[$ext]))
            $mimeType=$defaultMimeTypes[$ext];
        }
    }
    if(!$remoteFilename)
    {
        $remoteFilename=array_pop(explode('/',strtr($fileName,'\\','/')));
        header("Content-type: $mimeType");
        header("Content-Length: ".filesize($fileName));
        header("Content-Disposition: " . ($inline? "inline" : "attachment") . "; filename=\"$remoteFilename\"");
        header('Content-Transfer-Encoding: binary');
        readfile($fileName);
        exit();
    }
}

/**
 * Serializes an object to a string.
 *
 * This method is meant to replace the serialize() function
 * because the latter has a bug when serializing an object.
 * @param mixed the object to be serialized.
 * @return string the serialization result.
 * @see pradoUnserializeObject
 */
function pradoSerializeObject($object)
{
	$v=array();
	$v[0]=$object;  // don't serialize an object directly (a bug)
	return serialize($v);
}

/**
 * Unserializes an object from a string.
 *
 * This method is meant to replace the unserialize() function
 * because the latter has a bug when unserializing an object.
 * @param string the serialized data.
 * @return mixed the object unserialized from the string.
 * @see pradoSerializeObject
 */
function pradoUnserializeObject($str)
{
	$v=unserialize($str);
	if(!is_array($v) || count($v)!==1 || !isset($v[0]))
		throw new Exception('Unserialize failed due to incompatible serialized data.');
	return $v[0];
}


/**
* Returns a valid timestamp
*
* @param String date representation
* @param string date format representaton (GNU Style)
*
* Thanks to Spotk
* 
* @return int timestamp or null
*/
function pradoParseDate($string, $format)
{
	   $arDate = preg_split('/\W+/', $string);
		preg_match_all('/(%.)/', $format, $matchs);
	   
		if (!is_array($arDate) || !isset($matchs) || !is_array($matchs))
			return null;
		
		foreach($matchs[0] as $key) 
			$arFormat[] = substr($key, 1);
	   
		$day = 0;
		$month = 0;
		$year = 0;
		$hour = 0;
		$min = 0;
	   
	   if(count($arDate) != count($arFormat))
		   return null;

		foreach ($arFormat as $idx => $val) 
		{
	   
			switch($val) 
			{
			   
				case 'd':
				case 'e':
					$day = intval($arDate[$idx]);       
					break;
	   
				case 'm':
				case 'n':
					$month = intval($arDate[$idx]);
					break;
	   
				case 'y':
				case 'Y':
					$year = intval($arDate[$idx]);
					if ($year < 100) 
					{
						 $year = ($year < 29) ? $year += 2000 : $year+= 1900;
					}
					break;
	   
				case 'H':
				case 'I':
				case 'k':
				case 'l':
					$hour = intval($arDate[$idx]);
					break;
	   
				case 'P':
				case 'p':
					if ((stristr($arDate[$idx], 'pm')) && $hour < 12)
						$hour += 12;
					break;
			   
				case 'M':
					$min = intval($arDate[$idx]);
					break;
				   
				default:
					break;
			}
		}
	   
		if ($year !=0 && $month !=0 && $day != 0) 
		{		
			//return only valid dates
			return checkdate($month, $day, $year) ? mktime($hour, $min, 0, $month, $day, $year) : null;
		}
	   
		if ($month !=0 && $day != 0) 
		{
			$year = date('Y');
			return checkdate($month, $day, $year) ? mktime($hour, $min, 0, $month, $day, $year) : null;
		}
	   
		return null;
} 

/**
 * Check if a URL is absolute or not.
 * @param string the url to be checked
 * @return boolean true if the URL starts with http://, https://, or /
 */
function pradoIsAbsoluteUrl($url)
{
	return strpos($url,'http:')===0 || strpos($url,'https:')===0 || strpos($url,'/')===0;
}

?>