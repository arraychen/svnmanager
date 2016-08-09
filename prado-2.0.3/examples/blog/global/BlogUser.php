<?php

class BlogUser extends TUser
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

	public function onAuthenticationRequired($pageName)
	{
		$this->redirectUrl=$_SERVER['REQUEST_URI'];
		pradoGetApplication()->transfer('User:LoginPage');
	}

	public function onAuthorizationRequired($page)
	{
		$this->onAuthenticationRequired($page->ID);
	}
}

?>