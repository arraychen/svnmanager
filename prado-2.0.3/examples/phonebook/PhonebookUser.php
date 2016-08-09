<?php

class PhonebookUser extends TUser
{
	private $redirectUrl='';

	public function setRedirectUrl($url)
	{
		$this->redirectUrl=$url;
	}

	public function getRedirectUrl()
	{
		return $this->redirectUrl;
	}

	public function login($username,$password)
	{
		$succeed=($username==='root' && $password==='prado');
		if($succeed)
			$this->setUsername($username);
		$this->setAuthenticated($succeed);
		return $succeed;
	}

	public function onAuthenticationRequired($pageName)
	{
		$this->redirectUrl=$_SERVER['REQUEST_URI'];
		pradoGetApplication()->transfer('LoginPage');
	}

	public function onAuthorizationRequired($page)
	{
		$this->onAuthenticationRequired($page->ID);
	}
}

?>