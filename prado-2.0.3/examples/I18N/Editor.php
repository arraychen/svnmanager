<?php
/**
 * PRADO Translation Editor.
 * @author $Author: weizhuo $
 * @version $Id: Editor.php,v 1.6 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */
 
/**
 * PRADO Translation Editor class.
 * 
 * Provides a simple web interface to update message translations.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Tue Dec 28 18:05:30 EST 2004
 * @package prado.examples
 */
class Editor extends TPage
{
	
	/**
	 * Load the translation data.
	 * @param TEventParameter event parameter.
	 */		
	function onLoad($param)
	{
		parent::onLoad($param);
		if(!$this->isPostBack())
		{
			$app = $this->Application->getGlobalization();
			$type = $app->Translation['type'];
			$location = $app->Translation['source'];
				
			$this->initSource($type, $location);
		}
	}
	
	/**
	 * Change the inteface language.
	 * @param mixed sender details.
	 * @param TEventParameter event parameter.
	 */
	function changeLanguage($sender, $param)
	{
		//var_dump($param);
		$app = $this->Application->getGlobalization();
		$app->Culture = $param->parameter;
		$this->dataBind();
	}

	/**
	 * Initilize the translation data.
	 * @param string translation storage type. e.g. XLIFF
	 * @param string the translation source.
	 */
	function initSource($type, $location)
	{
		
		$catalouges = $this->Module->getCatalogues($type, $location);
		$this->CatalogueList->setDataSource($catalouges);
		$this->CatalogueList->setDataTextField(0);
		$this->CatalogueList->setDataValueField(0);
	
		$this->Source->setText($location);
		$this->Type->setSelectedValue($type);
			
		$this->dataBind();
	}
	
	/**
	 * Reload the translation source.
	 * @param mixed sender details.
	 * @param TEventParameter event parameter.
	 */
	function reloadSource($sender, $param)
	{
		$location = $this->Source->Text;
		$type = $this->Type->SelectedValue;
		$this->initSource($type, $location);
	}
	
	/**
	 * Construct the url to be used by the Dialog and MessageList Iframes.
	 * @param string the respective Dialog or MessageList class names.
	 * @return string the URL for the IFrame. 
	 */
	function getEditorURL($obj)
	{
		$app = $this->Application->getGlobalization();

		$source = $this->Source->Text;
		$type = $this->Type->SelectedValue;
		$lang = $app->Culture;
		
		$catalogue = $this->CatalogueList->getSelectedItem();
		
		$cat = null;
		
		if($catalogue)
			$cat = $catalogue->getText();
		else
		{
			$list = $this->CatalogueList->getItems();
			if(count($list)>0)
				$cat = $list[0]->getText();
		}
		
		$cats = explode('.', $cat);
		
		$class = $this->Module->ID.':'.$obj;
		$params = $this->Module->setSettings($source, $type, $cats, $lang);
		$url = $this->Request->constructURL($class, $params);

		return $url;
	}
}

?>