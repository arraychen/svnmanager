<?php

class LoginControl extends TControl 
{
	public function doLogin($sender, $params)
	{
		$this->loginControls->setVisible(false);
		$this->logoutControls->setVisible(true);
		$this->addBody('Thank you '.$this->Username->getText());
	}
	
	public function doLogout($sender, $params)
	{
		$this->loginControls->setVisible(true);
		$this->logoutControls->setVisible(false);
	}
}

?>