<?php

class EditPage extends TPage
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		if(!$this->IsPostBack)
		{
			$accnt=$this->Module->loadAccount();
			if(empty($accnt))
			{
				// Unexpected case.
				// This exception will be captured by the framework
				// You can also explicitly specify a page for ErrorHandler.
				throw new Exception('Unable to load user account information.');
			}
			$this->content->Username->setText($accnt['username']);
			$this->content->Password->setText($accnt['password']);
			$this->content->Password2->setText($accnt['password']);
			$this->content->Email->setText($accnt['email']);
		}
	}

	public function onClickUpdateBtn($sender,$param)
	{
		if($this->IsValid)
		{
			$accnt=array(
				'password'=>$this->content->Password->Text,
				'email'=>$this->content->Email->Text
				);
			if($this->Module->updateAccount($accnt))
				$this->Application->transfer();
			else
			{
				// Unexpected case.
				// This exception will be captured by the framework
				// You can also explicitly specify a page for ErrorHandler.
				throw new Exception('Unable to update account.');
			}
		}
	}
}

?>