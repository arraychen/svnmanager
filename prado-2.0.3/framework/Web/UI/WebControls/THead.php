<?php
/**
 * THead class file
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
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.2 $  $Date: 2005/03/21 14:26:47 $
 * @package System.Web.UI.WebControls
 */

/**
 * THead class
 *
 * This component is used to provide access to the &lt;head&gt; HTML 
 * element through prado code. You can access it via the 
 * 
 * 	<code>
 	$this->Page->Head
 	</code>
 * property. 
 * 
 * The THead component provides functionality that is also available through
 * the TPage component (it will remain in the TPage component for cases where a THead
 * component is not included on the page), including:
 * 
 * - <b>registerScriptFile</b>
 * - <b>registerStyleFile</b>
 *
 * Additionally, there are additional methods
 *
 * - <b>registerScriptBlock</b>
 * 	 <br/>Register script to be output between &lt;script&gt; tags
 * - <b>registerStyleBlock</b>
 * 	 <br/>Register script to be output between &lt;style&gt; tags
 * - <b>registerMetaInfo</b>
 *   <br/>Register information to be output in a &lt;meta&gt; tag
 *    
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>Title</b>, string, kept in viewstate
 *   <br/>Gets or sets the &lt;title&gt; of the page
 * 
 * Examples
 * - On a page template file, insert the following line to create a THead component,
 * <code>
 *   <com:THead Title="My Prado Page"/>
 * </code>
 * The checkbox will show "Agree" text on its right side. If the user makes any change
 * to the <b>Checked</b> state, the checkAgree() method of the page class will be invoked automatically.
 *
 * @author Marcus Nyeholt <tanus@users.sourceforge.net>
 * @version $Revision: 1.2 $
 * @package System.Web.UI.WebControls
 */
class THead extends TControl
{
	/**
	 * list of javascript files to be loaded by {@link THead}
	 * @var array
	 */
	private $scriptFiles=array();
	/**
	 * list of CSS style files to be loaded by {@link THead}
	 * @var array
	 */
	private $styleFiles=array();
	/**
	 * list of meta name tags to be loaded by {@link THead}
	 * @var array
	 */
	private $metaNameTags=array();

	/**
	* Get the title of the page
	* @return 	string		The page title.
	*/
	public function getTitle()
	{
		return $this->getViewState('title', '');
	}

	/**
	* Set the title of the page
	* @param 	string		$value		The page's title
	* @return 	void
	*/
	public function setTitle($value)
	{
		$this->setViewState('title', $value);
	}

	/**
	 * Registers a javascript file to be loaded in client side
	 * @param string a key that identifies the script file to avoid repetitive registration
	 * @param string the javascript file which can be relative or absolute URL
	 * @see isScriptFileRegistered()
	 */
	public function registerScriptFile($key,$scriptFile)
	{
		if ($this->isScriptFileRegistered($key))
		throw new Exception("Script file $key is already registered");
		$this->scriptFiles[$key] = $scriptFile;
	}

	/**
	 * Registers a CSS style file to be imported with the page body
	 * @param string a key that identifies the style file to avoid repetitive registration
	 * @param string the javascript file which can be relative or absolute URL
	 * @see isStyleFileRegistered()
	 */
	public function registerStyleFile($key,$styleFile)
	{
		if ($this->isStyleFileRegistered($key))
		throw new Exception("Style file $key is already registered");
		$this->styleFiles[$key] = $styleFile;
	}

	/**
	 * Registers a meta tag to be imported with the page body
	 * @param string a key that identifies the meta tag to avoid repetitive registration
	 * @param string the content of the meta tag
	 * @param string the language of the tag
	 * @see isMetaTagRegistered()
	 */
	public function registerMetaNameTag($key,$content,$lang='en')
	{
		if ($this->isMetaNameTagRegistered($key,$lang))
		throw new Exception("Meta name tag $key is already registered");
		$this->metaNameTags[$lang][$key] = $content;
	}

	/**
	 * Indicates whether the named scriptfile has been registered before.
	 * @param string the name of the scriptfile
	 * @return boolean 
	 * @see registerScriptFile()
	 */
	public function isScriptFileRegistered($key)
	{
		return isset($this->scriptFiles[$key]);
	}

	/**
	 * Indicates whether the named CSS style file has been registered before.
	 * @param string the name of the style file
	 * @return boolean 
	 * @see registerStyleFile()
	 */
	public function isStyleFileRegistered($key)
	{
		return isset($this->styleFiles[$key]);
	}

	/**
	 * Indicates whether the named meta tag has been registered before.
	 * @param string the name of tag
	 * @param string the lang of the tag
	 * @return boolean 
	 * @see registerMetaTag()
	 */
	public function isMetaNameTagRegistered($key,$lang='en')
	{
		return isset($this->metaNameTags[$lang][$key]);
	}

	/**
	* Render the &lt;head&gt; tag
	* @return the rendering result.
	*/
	public function render()
	{
		$body=$this->renderBody();
		$content = '<head '.$this->renderAttributes().">\n";
		$content .= $this->renderTitle();
		$content .= $this->renderMetaNameTags();
		$content .= $this->renderScripts();
		$content .= $this->renderStyles();
		$content .= $body;
		$content .= "\n</head>";

		return $content;

	}

	/**
	* Render the title tag
	* @return 	string		The rendering result
	*/
	private function renderTitle()
	{
		return '<title>'.$this->getTitle().'</title>'."\n";
	}

	/**
	 * Renders the registered javascript files to be included
	 * @return the rendering result
	 */
	public function renderScripts()
	{
		$content='';
		foreach($this->scriptFiles as $url)
		$content.="\n<script type=\"text/javascript\" src=\"$url\"></script>";
		return $content;
	}

	/**
	 * Renders the registered CSS style files to be included
	 * @return the rendering result
	 */
	public function renderStyles()
	{
		if(count($this->styleFiles)>0)
		{
			$content="\n<style type=\"text/css\" media=\"all\">\n<!--";
			foreach($this->styleFiles as $url)
			$content.="\n@import url($url);";
			$content.="\n-->\n</style>";
			return $content;
		}
		else
		return '';
	}

	/**
	 * Renders the registered meta tags to be included
	 * @return the rendering result
	 */
	public function renderMetaNameTags()
	{
		$content='';
		foreach($this->metaNameTags as $lang=>$array)
		{
			foreach($array as $key=>$tag)
			{
				$content.="\n<meta name=\"".$key."\" lang=\"".$lang."\" content=\"".$tag."\" />";
			}
		}
		return $content;
	}
}
?>