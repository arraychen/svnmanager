<?php

using('System.Data');

class LoginPage extends TPage
{
	public function onLogin($sender,$param)
	{
		if($this->User->login($this->Username->Text,$this->Password->Text))
		{
			$redirectUrl=$this->User->getRedirectUrl();
			if(empty($redirectUrl))
				$this->Application->transfer($this->Request->getDefaultPage());
			else
				$this->Application->redirect($redirectUrl);
		}
		else
			$param->isValid=false;
	}
}

?>