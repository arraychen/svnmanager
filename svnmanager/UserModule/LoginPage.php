<?php
/*
 * Created on 17-Jan-2005
 *
 */
class LoginPage extends TPage
{
	public function onLogin($sender,$param)
	{		
		$param->isValid=$this->User->login($this->Username->Text,$this->Password->Text);
	}

	public function onClickLoginBtn($sender,$param)
	{
		if($this->IsValid)
		{
			//$this->User->login($this->Username->Text);
			$redirectUrl=$this->User->getRedirectUrl();			
		
			if(empty($redirectUrl) || $this->Page)
				$this->Application->transfer();
			else
				$this->Application->redirect($redirectUrl);
		}
	}
}


?>
