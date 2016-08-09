<?php

require_once('svnmanager/global/AdminPageBase.php');

class AddPage extends AdminPageBase
{

	public function onInit($param)
	{
		parent::onInit($param);
		
	}
	
	public function onCheckUsername($sender, $param)
	{
		$username = strtolower($this->UserName->getText());
		if(!$this->Module->isUsernameTaken($username))
			$param->isValid=true;
		else
			$param->isValid=false;
		return;
	}

	public function onCheckPassword($sender, $param)
	{
		$userid = $this->User->getId();
		$password = $this->UserPassword->getText();		
		
		if($this->Module->checkPassword($userid, $password))
			$param->isValid=true;
		else
			$param->isValid=false;
		return;
	}
	
	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer('User:AdminPage');
	}
	
	public function onConfirmBtn($sender, $param)
	{
		//Add user if parameters are validated
		if($this->isValid())
		{
			$username = strtolower($this->UserName->getText());
			$password = $this->Password->getText();
			$email = $this->Email->getText();
			$grants = $this->Grants->getText();
			if($this->Admin->isChecked())
				$admin=255;
			else
				$admin=0;
						
			$this->Module->createAccount( $username, $password, $email, $admin, $grants );
			
			$this->AddPanel->setVisible(false);
			$this->ConfirmationPanel->setVisible(true);
		}
	}	

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("User:AdminPage");
	}
	
}
?>
