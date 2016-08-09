<?php
/**
 * TResourceLocator class file.
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
 * @version $Revision: 1.11 $  $Date: 2005/09/07 03:41:44 $
 * @package System
 */

/**
 * TResourceLocator class
 *
 * By default, TResourceLocator is used by the PRADO framework
 * to locate resource files including component specification
 * and component template files.
 *
 * You can specify your own locator class in the application specification.
 *
 * Namespace: System
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TResourceLocator
{
	/**
	 * name extension to specification files
	 */
	const EXT_SPECIFICATION='.spec';
	/**
	 * name extension to template files
	 */
	const EXT_TEMPLATE='.tpl';
	
	/**
	 * The absolute local URI storing javascript files.
	 * @var string
	 */
	protected $jsPath='js';
	/**
	 * The absolute local URI storing css files.
	 * @var string
	 */
	protected $cssPath='css';
	/**
	 * The absolute local URI storing image files.
	 * @var string
	 */
	protected $imagePath='images';

	/**
	 * Constructor.
	 * @param mixed configuration given in the application specification
	 */
	function __construct($config)
	{
		if(isset($config['js-path']) && strlen((string)$config['js-path']))
			$this->jsPath=rtrim((string)$config['js-path'],'/');
		if(isset($config['css-path']) && strlen((string)$config['css-path']))
			$this->cssPath=rtrim((string)$config['css-path'],'/');
		if(isset($config['image-path']) && strlen((string)$config['image-path']))
			$this->imagePath=rtrim((string)$config['image-path'],'/');
		$this->rootURI=rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\');
		if(!pradoIsAbsoluteUrl($this->jsPath))
			$this->jsPath=$this->rootURI.'/'.$this->jsPath;
		if(!pradoIsAbsoluteUrl($this->cssPath))
			$this->cssPath=$this->rootURI.'/'.$this->cssPath;
		if(!pradoIsAbsoluteUrl($this->imagePath))
			$this->imagePath=$this->rootURI.'/'.$this->imagePath;
	}

	/**
	 * Gets the content of the component specification.
	 * @param string the component type
	 * @return string the component specification content, empty if specification not found.
	 */
	public function getSpecification($type)
	{
		$class=new ReflectionClass($type);
		$fileName=dirname($class->getFileName()).'/'.$type.self::EXT_SPECIFICATION;
		if(is_file($fileName))
			return file_get_contents($fileName);
		else
			return '';
	}

	/**
	 * Gets the content of the component template
	 * @param string the component type
	 * @return string the component template content, empty if template not found.
	 */
	public function getTemplate($type)
	{
		$class=new ReflectionClass($type);
		
		$dir = dirname($class->getFileName()).'/';
		
		$fileName = $this->getResource($type, $dir, self::EXT_TEMPLATE);		

		if(is_file($fileName))
			return file_get_contents($fileName);
		else
			return '';
	}
	
	/**
	 * Locate the resource for a particular culture. The parameter
	 * $resource must be the filename without the file extension. 
	 * e.g. support a resource of indexpage.tpl, then the parameter
	 * $resource should be $resouce = 'indexpage';
	 * Resources are searched using "$resource.$prefix.$culture.$ext".
	 * Where each $culture is enumerated from "<LANG>_<COUNTYRY>_<VAIRANT>"
	 * "<LANG>_<COUNTYRY>" and then "<LANG>".
	 * If no culture specific resource is found, it returns the default
	 * "$resource.$ext".
	 *
	 * @param string $file the resource filename without extension
	 * @param string $dir the resource directory with ending slash
	 * @param string $ext the resource filename extension
	 * @param string $prefix the prefix before appending the culture. 
	 * Default is "."
	 * @param string $culture the culture for this resource, default is null
	 * such that it uses the application default culture.
     * @see TGlobalization::getVariants()
	 */
	public function getResource($file, $dir, $ext, $prefix='.',$culture=null)
	{
		$app = pradoGetApplication()->getGlobalization();
		if($app)
		{
			$variants = $app->getVariants($culture);
			foreach($variants as $variant)
			{
				$filename = $dir.$variant.'/'.$file.$ext;
				if(is_file($filename)) 
					return $filename;
			}
			foreach($variants as $variant)
			{
				$filename = $dir.$file.$prefix.$variant.$ext;
				if(is_file($filename))
					return $filename;
			}
		}
		
		return $dir.$file.$ext;
	}

	/**
	 * Gets the content of an external template.
	 * @param string the path alias to the external template.
	 * @return string the external template content
	 * @throw TTemplateNotExistsException
	 */
	public function getExternalTemplate($pathAlias)
	{
		$fname=pradoGetApplication()->translatePathAlias($pathAlias,self::EXT_TEMPLATE);
		if(is_null($fname) || !is_file($fname))
			throw new TTemplateNotExistsException($pathAlias);
		else
			return file_get_contents($fname);
	}

	/**
	 * @return string the absolute URI having the entry script
	 */
	public function getRootURI()
	{
		return $this->rootURI;
	}

	/**
	 * @return string the absolute URI having javascript files
	 */
	public function getJsPath()
	{
		return $this->jsPath;
	}

	/**
	 * @return string the absolute URI having css files
	 */
	public function getCssPath()
	{
		return $this->cssPath;
	}

	/**
	 * @return string the absolute URI having image files
	 */
	public function getImagePath()
	{
		return $this->imagePath;
	}

}

?>