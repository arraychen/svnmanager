<?php
/**
 * TService_RSS class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Wei Zhuo. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version $Revision: 1.1 $  $Date: 2005/03/26 07:18:41 $
 * @package System.Web.Services
 */

require_once(dirname(__FILE__).'/RSS/TRSSServer.php');


/**
 * TService_RSS class
 *
 * Allows RSS requests.
 *
 * @author Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on 2005/03/11 21:44:52
 * @package System.Web.Services
 */
class TService_RSS extends TService
{
	const service = '__RSS';
	
	protected $servers = array();
	
	function __construct($config)
	{
		$version = '2.0';
		if(isset($config['version']))
			$version = (string)$config['version'];
		
		$serverclass = 'TRSSServer';
		if(isset($config['class']))
			$serverclass = (string)$config['class'];
			
		foreach($config->class as $class)
		{
			$classpath = (string)$class['name'];
			$alias = (string)$class['alias'];
			
			$classname = $this->findClass($classpath);
			$rssHandler = new $classname;
			$name = empty($alias) ? $classpath : $alias;
			$this->servers[$name]['server'] = new $serverclass($rssHandler, $version);
			$this->servers[$name]['config'] = $class;
		}					
	}
			
	function IsRequestServiceable($request)
	{
		return isset($request[self::service]);
	}
	
	function execute()
	{
		if (isset($_SERVER['QUERY_STRING']) 
			&& strpos($_SERVER['QUERY_STRING'], self::service)!==false) 
		{
			$name = str_replace(self::service.'&', '', $_SERVER['QUERY_STRING']);
			if(isset($this->servers[$name]))
			{
				$this->servers[$name]['server']->execute();
				return;
			}
		}
		echo $this->renderServiceList();		
	}
	
	protected function renderServiceList()
	{
		$content = '';
		foreach($this->getServerList() as $server)
		{
			$content .= '<link rel="alternate" ';
			$content .= "type=\"{$server['type']}\" ";
			$content .= "title=\"{$server['title']}\" ";
			$href = htmlspecialchars($server['href']);
			$content .= "href=\"{$href}\" />\n";
		}
		return $content;
	}

	public function getServerList()
	{
		$servers = array();
		foreach($this->servers as $name => $server)
		{
			$description = trim((string)$server['config']['description']);
			if(empty($description)) $description = $name;
			$details['title'] = $description;
			$details['href'] = $this->path().'&'.$name;		
			$details['type'] = 'application/rss+xml';
			$servers[$name] = $details;
		}
		return $servers;
	}
	
	// Just a utility to help the example work out where the server URL is...
	protected function path() 
	{
		$basePath = $_SERVER['SCRIPT_NAME'];
		if ( isset($_SERVER['HTTPS']) )
			$scheme = 'https';
		else
			$scheme = 'http';
		return $scheme.'://'.$_SERVER['SERVER_NAME'].$basePath;
	}
}

class TRSSServiceException extends TException
{	
}

interface IRSSEventHandler
{
	public function getRSSInfo();

	public function getRSSItemList();

	public function getRSSDublicCore();

	public function getRSSSyndication();
}

class TRSSItem
{
	public $about = '';
	public $title = ''; 
	public $link = '';
	public $description = '';
	public $subject = '';
	public $date = 0;
	public $author = '';
	public $comments = '';
	public $image = '';
}

class TRSSInfo
{
	public $encoding = 'UTF-8';
	public $about = '';
	public $title = ''; 
	public $description = '';
	public $image_link = '';
	public $category = '';
}

/**
 *
 * @author $Author: weizhuo $
 * @version $Id: TService_RSS.php,v 1.1 2005/03/26 07:18:41 weizhuo Exp $
 */
class TRSSDublicCore 
{
	public $publisher = '';
	public $creator = '';
	public $date = 0;
	public $language = 'en';
	public $rights = '';
	public $coverage = '';
	public $contributor = '';
}

/**
 *
 * @author $Author: weizhuo $
 * @version $Id: TService_RSS.php,v 1.1 2005/03/26 07:18:41 weizhuo Exp $
 */
class TRSSSyndication 
{
	public $period = 'daily';
	public $frequency = 1;
	public $base = 0;
}

?>