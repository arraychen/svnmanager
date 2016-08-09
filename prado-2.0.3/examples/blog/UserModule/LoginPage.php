<?php

class LoginPage extends TPage
{
	public function onLogin($sender,$param)
	{
		$param->isValid=$this->Module->login($this->content->Username->Text,$this->content->Password->Text);
	}

	public function onClickLoginBtn($sender,$param)
	{
		if($this->IsValid)
		{
			$this->User->login($this->content->Username->Text,'');
			$redirectUrl=$this->User->getRedirectUrl();
			if(empty($redirectUrl))
				$this->Application->transfer();
			else
				$this->Application->redirect($redirectUrl);
		}
	}
}

?>