<?php
/**
 * TCacheManager class file.
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
 * @version $Revision: 1.6 $  $Date: 2005/03/23 02:21:04 $
 * @package System
 */
 
/**
 * TCacheManager class
 *
 * TCacheManager manages the caching of components.
 * The creation of a PRADO component may be time consuming because
 * it needs to read many files to finish the definition of a component.
 * TCacheManager provides a way to accelerate this process by
 * saving a serialized copy in memory and in file.
 * An instantiation of the same typed component can then be directly
 * unserialized from the copy which significantly shortens the time
 * needed for creation.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System
 */
class TCacheManager
{
	/**
	 * name extension to cache files (for storing serialized components)
	 */
	const EXT_CACHE='.bin';
	/**
	 * if the caching is enabled.
	 * @var boolean
	 */
	protected $enabled;
	/**
	 * the root cache path.
	 * @var string
	 */
	protected $rootPath;
	/**
	 * the current cache save path
	 * @var string
	 */
	protected $savePath;
	/**
	 * current module name
	 * @var string
	 */
	protected $currentModule='';
	/**
	 * list of components cached so far
	 * @var array
	 */
	protected $components=array();

	/**
	 * Constructor.
	 * Reads the configuration given in the app spec.
	 * In particular, the attribute 'enabled' and 'path' are read.
	 * @param mixed the configuration.
	 */
	function __construct($config)
	{
		if(isset($config['enabled']) && (string)$config['enabled']=='true')
			$this->enabled=true;
		else
			$this->enabled=false;
		if(empty($config['path']))
			$this->rootPath=null;
		else
		{
			$this->rootPath=realpath(pradoGetContextPath((string)$config['path'],dirname(pradoGetApplication()->getSpecificationFile())));
			if($this->rootPath===false || !is_dir($this->rootPath))
				throw new Exception("Unable to locate the cache path '{$this->rootPath}'.");
		}
		$this->savePath=$this->rootPath;
	}

	/**
	 * Sets the current module name.
	 * Since module is related with namespace partition, it is important
	 * for cache manager to know under which module (namespace) a component is created and cached.
	 * Module name affects the location a component is cached.
	 * @param string the module name
	 */
	public function setCurrentModule($name)
	{
		$this->currentModule=$name;
		if($this->enabled && !empty($this->rootPath))
		{
			$this->savePath=$this->rootPath.'/'.$name;
			if(!is_dir($this->savePath))
				@mkdir($this->savePath);
		}
	}

	/**
	 * Creates a component from cache.
	 *
	 * This method unserializes a component from memory or a file.
	 *
	 * Derived classes may override this method to provide other ways of caching component.
	 * Be sure to override {@link cacheComponent} to make it consistent.
	 * @param string the type of the component to be created
	 * @see cacheComponent()
	 * @return TComponent the component created, null if failed.
	 */
	public function cloneComponent($type)
	{
		if(!$this->enabled)
			return null;
		$id=empty($this->currentModule)?$type:$this->currentModule.'.'.$type;
		$data=null;
		if(isset($this->components[$id]))
			$data=unserialize($this->components[$id]);
		else if(!empty($this->savePath))
		{
			$cacheFile=$this->savePath.'/'.$type.self::EXT_CACHE;
			if(is_file($cacheFile))
			{
				$this->components[$id]=file_get_contents($cacheFile);
				$data=unserialize($this->components[$id]);
			}

		}
		if(is_array($data) && count($data)==2)
		{
			if($data[1] instanceof TPage)
				TComponent::setDefinition(null,$data[0]);
			else
				TComponent::setDefinition($type,$data[0]);
			return $data[1];
		}
		else
			return null;
	}

	/**
	 * Saves a component to cache.
	 *
	 * This method saves the serialized component into memory and as a file (if necessary).
	 *
	 * Derived classes may override this method to provide other ways of caching component.
	 * Be sure to override {@link cloneComponent} to make it consistent.
	 * @param TComponent the component to be cached
	 * @see cloneComponent()
	 */
	public function cacheComponent($component)
	{
		if(!$this->enabled)
			return;
		$type=get_class($component);
		$id=empty($this->currentModule)?$type:$this->currentModule.'.'.$type;
		if($component instanceof TPage)
			$data=array(TComponent::getDefinition(null),$component);
		else
			$data=array(TComponent::getDefinition($type),$component);
		$data=serialize($data);
		if(!isset($this->components[$id]))
		{
			$this->components[$id]=$data;
			if(!empty($this->savePath))
			{
				$cacheFile=$this->savePath.'/'.$type.self::EXT_CACHE;
				if(!file_exists($cacheFile))
				{
					$fp=fopen($cacheFile,"wb");
					if($fp)
					{
						if(flock($fp,LOCK_EX))
						{
							fputs($fp,$data);
							flock($fp,LOCK_UN);
						}
						fclose($fp);
					}
				}
			}
		}
	}
}

?>