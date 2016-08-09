<?php
/**
 * PRADO Translation Editor Dialog.
 * @author $Author: weizhuo $
 * @version $Id: Dialog.php,v 1.2 2005/08/04 05:27:17 weizhuo Exp $
 * @package prado.examples
 */

/**
 * Dialog for updating translations.
 * 
 * Update a message translation.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail.com>
 * @version v1.0, last update on Tue Dec 28 18:01:59 EST 2004
 * @package prado.examples
 */
class Dialog extends TPage
{
	
	/**
	 * Do a databind.
	 * @param TEventParameter event parameter.
	 */
	function onLoad($param) 
	{
		$this->dataBind();
		parent::onLoad($param);
	}
	
	/**
	 * Update the translation.
	 * @param mixed sender details.
	 * @param TEventParameter event parameter.
	 */
	function updateTranslation($sender, $param) 
	{
		$settings = $this->Module->getSettings();
		
		$source = $this->Source->Text;
		$target = $this->Target->Text;
		$comments = $this->Comments->Text;
		
		$result = $this->Module->updateMessage($source, $target, 
									 $comments, $settings);		
		if($result)
			$this->UpdateList->setText('true');
		else 
			$this->UpdateList->setText('false');
	}
}

?>