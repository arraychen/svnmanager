<?php
/**
 * THtmlArea class file
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
 * @version $Revision: 1.20 $  $Date: 2005/08/25 22:13:40 $
 * @package System.Web.UI.WebControls
 */

/**
 * TTextBox class file
 */
require_once(dirname(__FILE__).'/TTextBox.php');

/**
 * THtmlArea class
 *
 * THtmlArea wraps the visual editting functionalities provided by the
 * TinyMCE project {@link http://tinymce.moxiecode.com/}.
 *
 * THtmlArea displays a WYSIWYG text area on the Web page for user input
 * in the HTML format. The text displayed in the THtmlArea component is 
 * specified or determined by using the <b>Text</b> property.
 *
 * To enable the visual editting on the client side, set the property
 * <b>EnableVisualEdit</b> to true (which is default value).
 * To set the size of the editor when the visual editting is enabled, 
 * set the <b>Width</b> and <b>Height</b> properties instead of
 * <b>Columns</b> and <b>Rows</b> because the latter has no meaning
 * under the situation.
 *
 * To prevent the text displayed in the component from being modified,
 * set the <b>ReadOnly</b> property to true. (If <b>EnableVisualEdit</b>
 * is set to true, the user will still be able to modify the text on the client
 * side, however the server side text will not be changed. Future version
 * of this component may correct this inconsistency.)
 *
 * Note, <b>Text</b> will be HTML encoded before it is displayed in the THtmlArea component.
 * If you don't want it to be so, set <b>EncodeText</b> to false.
 *
 * Note, to use this component, you have to copy the directory
 * "<framework>/js/htmlarea" to the "js" directory which should be under
 * the directory containing the entry script file.
 *
 * Namespace: System.Web.UI.WebControls
 *
 * Properties
 * - <b>EnableVisualEdit</b>, boolean, default=true, kept in viewstate
 *   <br>Gets or sets whether WYSIWYG editting capability should be enabled.
 * - <b>Culture</b>, string, kept in viewstate
 *   <br>Gets or sets culture (language) of the displaying editor dialogs.
 * - <b>Options</b>, string, kept in viewstate
 *   <br>Gets or sets a list of options for the WYSIWYG (TinyMCE) editor.
 *   <br>See http://tinymce.moxiecode.com/tinymce/docs/index.html for TinyMCE manual.
 *
 * Compatibility
 * - The client-side visual editting capability is supported by
 * Internet Explorer 5.5+ for Windows, Mozilla 1.3+, Mozilla Firefox, Netscape 7.1+
 * and any other Gecko-based browser. If the browser does not support the visual editting,
 * a traditional textarea will be displayed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version v1.0, last update on 2004/08/13 21:44:52
 * @package System.Web.UI.WebControls
 */
class THtmlArea extends TTextBox
{
	/**
	 * URL (either relative or absolute) of javascript file that contains functions for visual editting
	 */
	const JS_HTMLAREA='tiny_mce/';

	/**
	 * Overrides the parent implementation.
	 * TextMode for THtmlArea control is always 'MultiLine'
	 * @return string the behavior mode of the THtmlArea component.
	 */
	public function getTextMode()
	{
		return $this->getViewState('TextMode','MultiLine');
	}

	/**
	 * Overrides the parent implementation.
	 * TextMode for THtmlArea is always 'MultiLine' and cannot be changed to others.
	 * @param string the text mode
	 */
	public function setTextMode($value)
	{
		throw new Exception("You cannot set 'TextMode' property.");
	}

	/**
	 * @return boolean whether to show WYSIWYG text editor
	 */
	public function isVisualEditEnabled()
	{
		return $this->getViewState('VisualEditEnabled',true);
	}

	/**
	 * Sets whether to show WYSIWYG text editor.
	 * @param boolean whether to show WYSIWYG text editor
	 */
	public function enableVisualEdit($value)
	{
		$this->setViewState('VisualEditEnabled',$value,true);
	}

	/**
	 * Gets the current culture.
	 * @return string current culture, e.g. en_AU.
	 */
	public function getCulture()
	{
		return $this->getViewState('Culture', '');
	}

	/**
	 * Sets the culture/language for the date picker.
	 * @param string a culture string, e.g. en_AU.
	 */
	public function setCulture($value)
	{
		$this->setViewState('Culture', $value, '');
	}

	/**
	 * Gets the list of options for the WYSIWYG (TinyMCE) editor
	 * @see http://tinymce.moxiecode.com/tinymce/docs/index.html
	 * @return string options
	 */
	public function getOptions()
	{
		return $this->getViewState('Options', '');
	}

	/**
	 * Sets the list of options for the WYSIWYG (TinyMCE) editor
	 * @see http://tinymce.moxiecode.com/tinymce/docs/index.html
	 * @param string options
	 */
	public function setOptions($value)
	{
		$this->setViewState('Options', $value, '');
	}

	/**
	 * Renders the HTMLArea
	 * @return string the rendering result
	 */
	public function render()
	{
		$text=$this->isEncodeText()?pradoEncodeData($this->getText()):$this->getText();
		if($this->isVisualEditEnabled())
		{
			$this->renderJsEditor();
		}
		
		if($this->isReadOnly())  // to disable the server-side text updating
			$this->setEnabled(false);
		
		$width=$this->getWidth();
        if (!strpos($width, '%')) 
        {
            if($width<450) $width=450; // to ensure proper display of visual toolbar
                $this->setWidth($width."px");
        }
        $height=$this->getHeight();
        if (!strpos($height, '%')) 
        {
            if($height<200) $height=200;
                $this->setHeight($height."px");
        }
		return '<textarea '.$this->renderAttributes().'>'.$text.'</textarea>';
	}

	protected function renderJsEditor()
	{
		$page = $this->Page;
		if(!$page->isScriptFileRegistered('THtmlArea'))
		{
			$path=$this->Application->getResourceLocator()->getJsPath().'/'.self::JS_HTMLAREA.'tiny_mce.js';
			$page->registerScriptFile('THtmlArea',$path);
			$script = "if(typeof(tinyMCE) == 'undefined') alert('Unable find javascript \"{$path}\"');";
			$page->registerEndScript('THtmlArea',$script);
		}
		
		$options = $this->renderJsOptions($this->getJsOptions());
		$script = "if(tinyMCE) tinyMCE.init({$options}); ";
		$page->registerEndScript('THtmlArea'.$this->ClientID,$script);
	}

	protected function renderJsOptions($options)
	{
		$keyPair = array();
		foreach($options as $key => $value)
			$keyPair[] = $key.':"'.addslashes($value).'"';
		return '{'.implode(', ', $keyPair).'}';		
	}

	protected function getJsOptions()
	{
		$options['mode'] = 'exact';
		$options['elements'] = $this->ClientID;
		$options['language'] = $this->getLanguageSuffix($this->getCulture());
		$options['theme'] = 'advanced';
		$options['theme_advanced_buttons1'] = 'bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright, justifyfull,separator,bullist,numlist,separator,undo,redo,separator,link,unlink,separator,code,help';
		$options['theme_advanced_buttons2'] = '';
		$options['theme_advanced_buttons3'] = '';
		$options['theme_advanced_toolbar_location'] = 'top';
		$options['theme_advanced_toolbar_align'] = 'left';
		$options['theme_advanced_path_location'] = 'bottom';
		$options['extended_valid_elements'] = 'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]';

		$options = array_merge($options, $this->parseOptions($this->getOptions()));

		return $options;
	}
	
	protected function parseOptions($string)
	{
		$options = array();
		$substrings = preg_split('/\n|,\n/', trim($string));
		foreach($substrings as $bits)
		{
			$option = explode(":",$bits);
			if(count($option) == 2)
				$options[trim($option[0])] = trim(preg_replace('/\'|"/','',  $option[1]));
		}
		return $options;
	}

	protected function getLanguageSuffix($culture)
	{		
		if(empty($culture))
		{
			$app = $this->Application->getGlobalization();			
			if(!is_null($app))
				$culture = $app->Culture;
		}
		return empty($culture) ? 'en' : strtolower($culture);
	}
}

?>