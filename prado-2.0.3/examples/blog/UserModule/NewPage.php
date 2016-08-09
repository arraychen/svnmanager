<?php

class NewPage extends TPage
{
	public function onInit($param)
	{
		parent::onInit($param);
		if($this->Module->getUserParameter('AllowNewAccount')==='false')
			$this->Application->transfer();
	}

	public function onClickRegisterBtn($sender,$param)
	{
		if($this->IsValid)
		{
			$accnt=array(
				'username'=>$this->content->Username->Text,
				'password'=>$this->content->Password->Text,
				'email'=>$this->content->Email->Text
				);
			if($this->Module->createAccount($accnt))
			{
				$this->User->login($accnt['username'],'');
				$this->Application->transfer();
			}
			else
			{
				// Unexpected case.
				// This exception will be captured by the framework
				// You can also explicitly specify a page for ErrorHandler.
				throw new Exception('Unable to create account.');
			}
		}
	}

	public function isUsernameTaken($sender,$param)
	{
		$param->isValid=!$this->Module->isUsernameTaken($this->content->Username->Text);
	}
}

?>