<?php
/**
 * TForm class file
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
 * @version $Revision: 1.13 $  $Date: 2005/10/09 10:24:12 $
 * @package System.Web.UI
 */

/**
 * TForm class
 *
 * TForm creates a form on the page.  You can create at most one TForm component on a page
 * and it should enclose other components that render form fields, such as TButton, TTextBox, etc.
 *
 * TForm always submits to the page containing the TForm. If you need to submit to a different page,
 * use 'form' tag directly. This may seem inconvient at first sight. Thinking in the other way,
 * however, we shall see a single TForm enables the event-driven programming mechanism.
 *
 * Namespace: System.Web.UI
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI
 */
class TForm extends TControl
{
	/**
	 * list of hidden fields to be rendered by {@link TForm}
	 * @var array
	 */
	private $hiddenFields=array();
	/**
	 * list of javascripts to be rendered right after the openning element of {@link TForm}
	 * @var array
	 */
	private $beginScripts=array();
	/**
	 * list of javascripts to be rendered right before the closing element of {@link TForm}
	 * @var array
	 */
	private $endScripts=array();
	/**
	 * list of array definitions to be rendered as javascripts within {@link TForm}, indexed by array names
	 * @var array
	 */
	private $arrayScripts=array();
	/**
	 * list of javascript statements to be rendered as "onsubmit" attribute value of {@link TForm}
	 * @var array
	 */
	private $onSubmitScripts=array();
	/**
	 * list of javascript files to be loaded by {@link TForm}
	 * @var array
	 */
	private $scriptFiles=array();
	/**
	 * list of CSS style files to be loaded by {@link TForm}
	 * @var array
	 */
	private $styleFiles=array();

	/**
	 * Get the form action (i.e. where the form is submitted to).
	 * Default is the current request URI.
	 * @return string form action attribute value.
	 */
	public function getAction()
	{
		return isset($this->action) ? $this->action :
				pradoEncodeData($_SERVER['REQUEST_URI']); //is this right?
	}

	/**
	 * Sets the for value for the action attribute of the form.
	 * Changing the value of the form, changes the destination of the form.
	 * @param string new destination for the form.
	 */
	public function setAction($value)
	{
		$this->action = $value;
	}

	/**
	 * This overrides the parent implementation by rendering TForm-specific content.
	 * @return the rendering result.
	 */
	public function render()
	{
		$body=$this->renderBody(); // render body first in case the contained control generates more JS
		$name=$this->getUniqueID();
		$action = $this->getAction();
		$content="<form action=\"$action\" method=\"post\" enctype=\"multipart/form-data\"";
		$onsubmit=$this->renderOnSubmitStatements();
		if(strlen($onsubmit))
			$content.=" onsubmit=\"$onsubmit\"";
		$content.=' '.$this->renderAttributes().'>';
		$content.=$this->renderHiddenFields();
		$content.=$this->renderBeginScripts();
		$content.=$this->renderStyleFiles();
		$content.=$this->renderScriptFiles();
		$content.=$body;
		$content.=$this->renderArrayScripts();
		$content.=$this->renderEndScripts();
		$content.="</form>";
		return $content;
	}

	/**
	 * Renders the registered hidden fields
	 * @return the rendering result
	 */
	public function renderHiddenFields()
	{
		$content='';
		foreach($this->hiddenFields as $name=>$value)
			$content.="\n<div><input type=\"hidden\" name=\"$name\" value=\"$value\" /></div>";
		return $content;
	}

	/**
	 * Renders the registered onsubmit statements
	 * @return the rendering result
	 */
	public function renderOnSubmitStatements()
	{
		return implode(';',$this->onSubmitScripts);
	}

	/**
	 * Renders the registered javascript array declarations
	 * @return the rendering result
	 */
	public function renderArrayScripts()
	{
		if(count($this->arrayScripts)>0)
		{
			$content="
<script type=\"text/javascript\">
//<![CDATA[
";
			foreach($this->arrayScripts as $name=>$value)
				$content.="var $name=new Array($value);\n";
			$content.="
//]]>
</script>
";
			return $content;
		}
		else
			return '';
	}

	/**
	 * Renders the registered javascripts showing at the beginning of the TForm control
	 * @return the rendering result
	 */
	public function renderBeginScripts()
	{
		if(count($this->beginScripts)>0)
		{
			$content="
<script type=\"text/javascript\">
//<![CDATA[
";
			$content.=implode("\n",$this->beginScripts);
			$content.="
//]]>
</script>
";
			return $content;
		}
		else
			return '';
	}

	/**
	 * Renders the registered javascripts showing at the end of the TForm control
	 * @return the rendering result
	 */
	public function renderEndScripts()
	{
		if(count($this->endScripts)>0)
		{
			$content="
<script type=\"text/javascript\">
//<![CDATA[
";
			$content.=implode("\n",$this->endScripts);
			$content.="
//]]>
</script>
";
			return $content;
		}
		else
			return '';
	}

	/**
	 * Renders the registered javascript files to be included
	 * @return the rendering result
	 */
	public function renderScriptFiles()
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
	public function renderStyleFiles()
	{
		if(count($this->styleFiles)>0)
		{
			$content='<style type="text/css" media="all"><!--';
			foreach($this->styleFiles as $url)
				$content.="\n@import url($url);";
			$content.="\n--></style>";
			return $content;
		}
		else
			return '';
	}


	/**
	 * Registers a hidden field to be submitted upon client postback event.
	 * @param string name of the hidden field
	 * @param string value of the hidden field
	 */
	public function registerHiddenField($name,$value)
	{
		$this->hiddenFields[$name]=$value;
	}

	public function getHiddenFieldValue($name)
	{
		return $this->hiddenFields[$name];
	}

	/**
	 * Indicates whether the named hidden field has been registered before.
	 * @param string the name of the hidden field
	 * @return boolean
	 * @see registerHiddenField()
	 */
	public function isHiddenFieldRegistered($name)
	{
		return isset($this->hiddenFields[$name]);
	}

	/**
	 * Registers a javascript statement to be executed upon client postback event.
	 * @param string a key that identifies the statement to avoid repetitive registration
	 * @param string the javascript statement to be registered
	 */
	public function registerOnSubmitStatement($key,$script)
	{
		$this->onSubmitScripts[$key]=$script;
	}

	/**
	 * Indicates whether the named onsubmit statement has been registered before.
	 * @param string the key that identifies the onsubmit statement
	 * @return boolean
	 * @see registerOnSubmitStatement()
	 */
	public function isOnSubmitStatementRegistered($name)
	{
		return isset($this->onSubmitScripts[$name]);
	}

	/**
	 * Register an element of a javascript array to be created on client side.
	 * The elements of multiple registration of the same array name will be merged together.
	 * @param string the name of the array
	 * @param string the value of the array element
	 */
	public function registerArrayDeclaration($name,$value)
	{
		if(isset($this->arrayScripts[$name]))
			$this->arrayScripts[$name].=', '.$value;
		else
			$this->arrayScripts[$name]=$value;
	}

	/**
	 * Indicates whether the named array has been registered before.
	 * @param string the array name
	 * @return boolean
	 * @see registerArrayDeclaration()
	 */
	public function isArrayDeclarationRegistered($name)
	{
		return isset($this->arrayScripts[$name]);
	}

	/**
	 * Indicates whether the named beginscript has been registered before.
	 * @param string the key that identifies a beginscript
	 * @return boolean
	 * @see registerBeginScript()
	 */
	public function isBeginScriptRegistered($key)
	{
		return isset($this->beginScripts[$key]);
	}

	/**
	 * Registers a javascript block to be rendered right after the openning form element.
	 * @param string a key that identifies the script block to avoid repetitive registration
	 * @param string the javascript block
	 * @see isBeginScriptRegistered()
	 */
	public function registerBeginScript($key,$script)
	{
		$this->beginScripts[$key]=$script;
	}

	/**
	 * Indicate whether the named endscript has been registered before.
	 * @param string the key that identifies a beginscript
	 * @return boolean
	 * @see registerEndScript()
	 */
	public function isEndScriptRegistered($key)
	{
		return isset($this->endScripts[$key]);
	}

	/**
	 * Register a javascript block to be rendered right before the closing form element.
	 * @param string a key that identifies the script block to avoid repetitive registration
	 * @param string the javascript block
	 * @see isEndScriptRegistered()
	 */
	public function registerEndScript($key,$script)
	{
		$this->endScripts[$key]=$script;
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
	 * Registers a javascript file to be loaded in client side
	 * @param string a key that identifies the script file to avoid repetitive registration
	 * @param string the javascript file which can be relative or absolute URL
	 * @see isScriptFileRegistered()
	 */
	public function registerScriptFile($key,$scriptFile)
	{
		$this->scriptFiles[$key]=$scriptFile;
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
	 * Registers a CSS style file to be imported with the page body
	 * @param string a key that identifies the style file to avoid repetitive registration
	 * @param string the javascript file which can be relative or absolute URL
	 * @see isStyleFileRegistered()
	 */
	public function registerStyleFile($key,$styleFile)
	{
		$this->styleFiles[$key]=$styleFile;
	}
}

?>