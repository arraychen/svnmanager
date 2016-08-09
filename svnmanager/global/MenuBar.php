<?php
/*
 * Created on 18-Jan-2005
 *
 */
class MenuBar extends TControl
{
	public function onInit($param)
	{
		parent::onInit($param);	
	}

	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->dataBind();		
	}
	
	public function onClickLoginBtn($sender, $param)
	{
		$this->User->onAuthorizationRequired($this->Page);
	}	

	public function onClickLogoutBtn($sender, $param)
	{
		$this->User->logout();
		$this->Application->transfer("Main:StartPage");
	}

	public function onClickUserBtn($sender, $param)
	{
		$this->Application->transfer("User:AdminPage");
	}
	
	public function onClickGroupBtn($sender, $param)
	{
		$this->Application->transfer("Group:AdminPage");
	}

	public function onClickRepositoryBtn($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}
}
 
?>
