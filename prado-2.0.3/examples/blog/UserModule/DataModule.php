<?php

using('System.Data');

class DataModule extends TModule
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->Database->setDataSourceName($this->Application->getUserParameter('DSN'));
	}
	
	public function loadAccount($username='')
	{
		if(empty($username))
			$username=$this->User->getUsername();
		$result=$this->Database->Execute("SELECT * FROM tblUser WHERE username='$username'");
		if($result->RecordCount()>0)
			return $result->fields;
		else
			return null;
	}

	public function createAccount($accnt)
	{
		return $this->Database->Execute("INSERT INTO tblUser (username,email,password) VALUES ('{$accnt['username']}','{$accnt['email']}','{$accnt['password']}')");
	}

	public function updateAccount($accnt)
	{
		$username=isset($accnt['username'])?$accnt['username']:$this->User->getUsername();
		return $this->Database->Execute("UPDATE tblUser SET email='{$accnt['email']}', password='{$accnt['password']}' WHERE username='$username'");
	}

	public function isUsernameTaken($username)
	{
		$result=$this->Database->Execute("SELECT * FROM tblUser WHERE username='$username'");
		return $result->RecordCount()>0;
	}

	public function login($username,$password=null)
	{
		if(is_null($password))
			$authenticated=true;
		else
		{
			$result=$this->Database->Execute("SELECT * FROM tblUser WHERE username='$username' AND password='$password'");
			$authenticated=$result->RecordCount()>0;
		}
		return $authenticated;
	}

	public function logout()
	{
		$this->User->logout();
	}
}

?>