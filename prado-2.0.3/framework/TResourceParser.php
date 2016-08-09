<?php
/**
 * TResourceParser class file.
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
 * @version $Revision: 1.11 $  $Date: 2005/05/29 07:09:19 $
 * @package System
 */

/**
 * TResourceParser class
 *
 * By default, TResourceParser is used by the PRADO framework
 * to parse various resource files including component specifications 
 * and component templates.
 *
 * You can specify your own parser class in the application specification.
 *
 * Namespace: System
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TResourceParser
{
	/**
	 * Constructor.
	 * @param mixed configuration given in the application specification
	 */
	function __construct($config)
	{
	}
	
	/**
	 * Parses a component specification string.
	 * The specification is in XML format and is parsed using simpleXML.
	 * @param string the specification string
	 * @return array|null the parsed result, null if parsing failed
	 */
	public function parseSpecification($str)
	{
		$spec=simplexml_load_string($str);
		if($spec===false)
			return null;
		$properties=array();
		$events=array();
		$components=array();
		foreach($spec->property as $property)
			$properties[]=array((string)$property['name'],(string)$property['type'],(string)$property['get'],(string)$property['set']);
		foreach($spec->event as $event)
			$events[]=(string)$event['name'];
		foreach($spec->component as $component)
		{
			$type=(string)$component['type'];
			$id=(string)$component['ID'];
			$properties=array();
			foreach($component->property as $property)
				$properties[(string)$property['name']]=(string)$property['value'];
			foreach($component->event as $event)
				$events[(string)$event['name']]=(string)$event['handler'];
			$components[]=array($type,$id,$properties,$events);
		}
		return array('property'=>$properties,'event'=>$events,'component'=>$components);
	}

	/**
	 * Parses a template string.
	 * @param string the template string
	 * @return array the parsed result
	 * @throw TTagUnbalancedException
	 */
	public function parseTemplate($input)
	{
		$str=$this->preprocessTemplate($input);

		$patterns[] = '<!--|-->'; //comments
		$patterns[] = '<\/?prop:([\w\-]+)\s*>'; //property
		$patterns[] = '<com:(\w+)((\s*[\w\-]+=\'.*?\'|\s*[\w\-]+=".*?")*)\s*\/?>'; //component
		$patterns[] = '<\/com:(\w+)\s*>'; //default component
		$patterns[] = '<%@\s*(\w+)\s+((\s*[\w\-]+=\'.*?\'|\s*[\w\-]+=".*?")*)\s*%>';//directives
		$patterns[] = '<%(.*?)%>'; //expression
						
		$pattern = '/'.implode('|',$patterns).'/ms';
		
		$n=preg_match_all($pattern,$str,$matches,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);		
		$state=0;	// 0: normal, 1: expecting comment end, 2: expecting property end
		$textStart=0;
        $stack=array();
		$tpl=array();
		$container=-1;
		$c=0;
		// there are two types of objects:
		// - string: 0: container index; 1: string content
		// - component: 0: container index; 1: type; 2: id; 3: attributes (array)
		for($i=0;$i<$n;++$i)
		{
			$match=$matches[$i][0][0];
			$matchStart=$matches[$i][0][1];
			$matchEnd=$matchStart+strlen($match)-1;
			if($match==='<!--')
			{
				if($state==2)
					continue;
				$state=1;
				array_push($stack,'<!--');
			}
			else if($match==='-->')
			{
				if($state!=1)
					continue;
				if(empty($stack) || array_pop($stack)!=='<!--')
					throw new TTagUnbalancedException('<!--');
				$state=0;
			}
			else if(strpos($match,'<prop:')===0)
			{
				if($state==1)
					continue;
				$prop=$matches[$i][1][0];
				if($state==2)
					array_push($stack,"!prop:$prop");
				else
				{
					array_push($stack,"prop:$prop");
					if($matchStart>$textStart)
						$tpl[$c++]=array($container,substr($str,$textStart,$matchStart-$textStart));
					$textStart=$matchEnd+1;
					$state=2;
				}
			}
			else if(strpos($match,'</prop:')===0)
			{
				if($state!==2)
					continue;
				$prop=$matches[$i][1][0];
				$previous=empty($stack)?'':array_pop($stack);
				if($previous==="!prop:$prop")
					continue;
				else if($previous==="prop:$prop")
				{
					if($matchStart>$textStart && $container>=0)
					{
						$tpl[$container][3][$prop]=substr($str,$textStart,$matchStart-$textStart);
						$textStart=$matchEnd+1;
					}
					$state=0;
				}
				else
					throw new TTagUnbalancedException("prop:$prop");
			}
			else if(strpos($match,'<com:')===0)
			{
				if($state==1 || $state==2)
					continue;
				if($matchStart>$textStart)
					$tpl[$c++]=array($container,substr($str,$textStart,$matchStart-$textStart));
				$textStart=$matchEnd+1;
				$type=$matches[$i][2][0];
				if(count($matches[$i])>3)
				{
					$attributes=$this->parseAttributes($matches[$i][3][0]);
					if(isset($attributes['ID']))
					{
						$id=$attributes['ID'];
						unset($attributes['ID']);
					}
					else
						$id='';
					$tpl[$c++]=array($container,$type,$id,$attributes);
				}
				else
					$tpl[$c++]=array($container,$type,'',array());
				if($match{strlen($match)-2}!=='/')  // open tag
				{
					array_push($stack,"com:$type");
					$container=$c-1;
				}
			}
			else if(strpos($match,'</com:')===0)
			{
				if($state==1 || $state==2)
					continue;
				if($matchStart>$textStart)
					$tpl[$c++]=array($container,substr($str,$textStart,$matchStart-$textStart));
				$textStart=$matchEnd+1;
				$type=$matches[$i][5][0];
				if(empty($stack) || array_pop($stack)!=="com:$type")
					throw new TTagUnbalancedException("com:$type");
				$container=$tpl[$container][0];
			}
			else if(strpos($match, '<%@') === 0)
			{
				//do directives
				
				if($state==1 || $state==2)
					continue;
			
				$textStart=$matchEnd+1;
				
				$type = $matches[$i][6][0];
				if(count($matches[$i])>6)
				{
					$attributes=$this->parseAttributes($matches[$i][7][0]);					
					$tpl[$c++]=array('directive',$type,$attributes);					
				}	
				
			}
			else if(strpos($match,'<%')===0)
			{
				if($state==1 || $state==2)
					continue;
				if($matchStart>$textStart)
					$tpl[$c++]=array($container,substr($str,$textStart,$matchStart-$textStart));
				$textStart=$matchEnd+1;
				$data=$matches[$i][9][0];
				if(strlen($data))
				{
					if($data{0}==='=')
						$tpl[$c++]=array($container,'TExpression','',array('Expression'=>substr($data,1)));
					else
						$tpl[$c++]=array($container,'TStatements','',array('Statements'=>$data));
				}
			}
			else
				throw new Exception("Unexpected matching: $match. Please report this problem to PRADO developers.");
		}
		if(!empty($stack))
		{
			$tag=array_pop($stack);
			throw new TTagUnbalancedException($tag);
		}
		if($textStart<strlen($str))
			$tpl[$c++]=array($container,substr($str,$textStart));
		return $tpl;
	}

	/**
	 * Parses the attributes of a tag from a string.
	 * @param string the string to be parsed.
	 * @return array attribute values indexed by names.
	 */
	protected function parseAttributes($str)
	{
		$pattern='/([\w\-]+)=(\'.*?\'|".*?")/ms';
		$attributes=array();
		$n=preg_match_all($pattern,$str,$matches,PREG_SET_ORDER);
		for($i=0;$i<$n;++$i)
			$attributes[$matches[$i][1]]=substr($matches[$i][2],1,strlen($matches[$i][2])-2);
		return $attributes;
	}

	/**
	 * Preprocesses a template string by inserting external templates (recursively).
	 * @param string the template string to be preprocessed
	 * @return string the processed result
	 */
	protected function preprocessTemplate($str)
	{
		if($n=preg_match_all('/<%include(.*?)%>/',$str,$matches,PREG_SET_ORDER|PREG_OFFSET_CAPTURE))
		{
			$base=0;
			for($i=0;$i<$n;++$i)
			{
				$pathAlias=trim($matches[$i][1][0]);
				$ext=$this->preprocessTemplate(pradoGetApplication()->getResourceLocator()->getExternalTemplate($pathAlias));
				$length=strlen($matches[$i][0][0]);
				$offset=$base+$matches[$i][0][1];
				$str=substr_replace($str,$ext,$offset,$length);
				$base+=strlen($ext)-$length;
			}
		}
		return $str;
	}

	/**
	 * Parses a theme specification string.
	 * The specification is in XML format and is parsed using simpleXML.
	 * @param string the specification string
	 * @return array|null the parsed result, null if parsing failed
	 */
	public function parseTheme($str)
	{
		$spec=simplexml_load_string($str);
		if($spec===false)
			return null;
		$theme=array();
		$theme['name']=(string)$spec['name'];
		foreach($spec->skin as $skin)
		{
			$componentName=(string)$skin['component'];
			$skinName=(string)$skin['name'];
			if(!strlen($skinName))
				$skinName="_default";
			$parentName=(string)$skin['extends'];
			
			$properties=array();
			foreach ($skin->property as $property)
				$properties[ (string)$property['name'] ] = (string)$property['value'];
				
			if (isset($theme['skins'][$componentName][$skinName])) {
				if ($skinName != 0)
					throw new TThemeParsingFailedException("A default skin has already been declared for $componentName");
				else
					throw new TThemeParsingFailedException("A skin called $skinName has already been declared for $componentName");
			}
			
			$theme['skins'][$componentName][$skinName] = array('properties'=>$properties);
			if (strlen($parentName))
				$theme['skins'][$componentName][$skinName]['parent']=$parentName;
		}
		return $theme;
	}
}

// TODO: pinpoint the location that the error happens during parsing


?>