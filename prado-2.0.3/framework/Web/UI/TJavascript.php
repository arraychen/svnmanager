<?php
/**
 * TJavascript class file.
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
 * @version $Revision: 1.3 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI
 */

/**
 * TJavascript class file. Javascript utilties, converts basic PHP types into 
 * appropriate javascript types.
 *
 * Example:
 * <code> 
 * $options['onLoading'] = "doit";
 * $options['onComplete'] = "more";
 * $js = TJavascript::toList($options); 
 * //expects the following javascript code
 * // {'onLoading':'doit','onComplete':'more'}
 * </code>
 *
 * Namespace: System.Web.UI
 *
 * @author Wei Zhuo<weizhuo[at]gmail[dot]com>
 * @version $Revision: 1.3 $  $Date: 2005/11/10 23:43:26 $
 * @package System.Web.UI
 */
class TJavascript
{
	/**
	 * Render a block of of javavscript code within a javascript tag.
	 */
	public static function render($code)
	{
		$contents = <<<EOD
<script type="text/javascript">
//<![CDATA[
	{$code}
//]]>
</script>
EOD;
		return $contents;
	}
	
	/**
	 * Coverts PHP arrays (only the array values) into javascript array.
	 * @param array the array data to convert
	 * @param string append additional javascript array data
	 * @param boolean if true empty string and empty array will be converted
	 * @return string javascript array as string.
	 */
	public static function toArray($array,$append=null,$strict=false)
	{
		$results = array();
		$converter = new TJavascript();
		foreach($array as $v)
		{
			if($strict || (!$strict && $v !== '' && $v !== array()))
			{
				$type = 'to_'.gettype($v);
				if($type == 'to_array') 
					$results[] = $converter->toArray($v, $append, $strict);
				else
					$results[] = $converter->{$type}($v);
			}
		}
		$extra = '';
		if(strlen($append) > 0)
			$extra .= count($results) > 0 ? ','.$append : $append;	
		return '['.implode(',', $results).$extra.']';
	}
	
	/**
	 * Coverts PHP arrays (both key and value) into javascript objects.
	 * @param array the array data to convert
	 * @param string append additional javascript object data
	 * @param boolean if true empty string and empty array will be converted
	 * @return string javascript object as string.
	 */	
	public static function toList($array,$append=null, $strict=false)
	{
		$results = array();
		$converter = new TJavascript();
		foreach($array as $k => $v)
		{
			if($strict || (!$strict && $v !== '' && $v !== array()))
			{
				$type = 'to_'.gettype($v);
				if($type == 'to_array')
					$results[] = "'{$k}':".$converter->toList($v, $append, $strict);
				else
					$results[] = "'{$k}':".$converter->{$type}($v);
			}
		}
		$extra = '';
		if(strlen($append) > 0)
			$extra .= count($results) > 0 ? ','.$append : $append;
			
		return '{'.implode(',', $results).$extra.'}';		
	}
	
	public function to_boolean($v)
	{
		return $v ? 'true' : 'false';
	}
	
	public function to_integer($v)
	{
		return "{$v}";
	}
	
	public function to_double($v)
	{
		return "{$v}";
	}
	
	/**
	 * If string begins with [ and ends ], or begins with { and ends }
	 * it is assumed to be javascript arrays or objects and no further
	 * conversion is applied.
	 */
	public function to_string($v)
	{
		if(strlen($v)>1)
		{
			$first = $v{0}; $last = $v{strlen($v)-1};		
			if($first == '[' && $last == ']' ||
				($first == '{' && $last == '}'))
				return $v;
		}
		return "'".addslashes($v)."'";
	}
	
	public function to_array($v)
	{
		return TJavascript::toArray($v);
	}
	
	public function to_null($v)
	{
		return 'null';
	}
}

?>