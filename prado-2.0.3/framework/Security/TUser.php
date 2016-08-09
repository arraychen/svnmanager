<?php
/**
 * TUser class file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2004 by Qiang Xue. All rights reserved.
 *
 * To contact the author write to {@link mailto:qiang.xue@gmail.com Qiang Xue}
 * The latest version of PRADO can be obtained from:
 * {@link http://prado.sourceforge.net/}
 *
 * @author Qiang Xue <qiang.xue@gmail.com>, Tim Evans <tim.evans@gmail.com>
 * @version $Revision: 1.7 $  $Date: 2005/01/04 21:35:10 $
 * @package System.Security
 */

/**
 * TUser class
 *
 * TUser class is a very basic implementation of IUser interface.
 *
 * TUser is meant to be as the base class of other user classes (but not the only one.)
 * TUser uses username and password as the authentication elements.
 * You can access the username by the property <b>Username</b>.
 * Whether the user is authenticated can be determined by the property
 * <b>IsAuthenticated</b>.
 *
 * Derived classes should override {@link login} and {@link logout} methods
 * to accomplish the real authentication work (such as authenticating
 * the user/password against DB).
 * If Roles are used for authorization, {@link isInRole} should also be overriden.
 * The methods {@link onAuthenticationRequired} and {@link onAuthorizationRequired}
 * will be invoked by the framework when authentication and authorization fail, respectively.
 * Default implementation simply displays an error message.
 * In most cases, the two methods should be overriden to provide
 * customized handlings (such as transfer to the login page).
 * 
 * Namespace: System.Security
 *
 * Properties
 * - <b>Username</b>, string
 *   <br>Gets or sets the username of TUser.
 * - <b>IsAuthenticated</b>, boolean, default=true
 *   <br>Gets or sets whether the user passes the authentication.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>, Tim Evans <tim.evans@gmail.com>
 * @version v1.0, last update on 2004/10/24 12:00:00
 * @package System.Security
 */
class TUser implements IUser
{
	/**
	 * whether the user passes the authentication
	 * @var boolean
	 */
	private $authenticated=false;
	/**
	 * username of TUser
	 * @var string
	 */
	private $username='';

	/**
	 * Constructor.
	 * @param mixed the app spec related with user class.
	 */
	function __construct($config)
	{
	}

	/**
	 * Checks if the user is of certain role.
	 * Default implementation will only return true if the role to be checked is empty.
	 * Derived classes should override this method if roles are used for authorization.
	 * @param string the role to be checked
	 * @return boolean if the user is of the role
	 */
	public function isInRole($role)
	{
		return empty($role);
	}

	/**
	 * This method is invoked by the framework when authentication fails.
	 * Default implementation simply displays an error message.
	 * Derived classes may override this method to provide customized treatment
	 * when authentication fails (e.g. transfer to login page)
	 * @param string the name of the page that requires authentication.
	 */
	public function onAuthenticationRequired($pageName)
	{
		echo 'You need to login to access the page '.$pageName.'.';
		exit();
	}

	/**
	 * This method is invoked by the framework when authorization fails.
	 * Default implementation simply displays an error message.
	 * Derived classes may override this method to provide customized treatment
	 * when authorization fails (e.g. transfer to login page)
	 * @param TPage the page object that are not authorized to be accessed.
	 */
	public function onAuthorizationRequired($page)
	{
		echo 'You are not allowed to access the page '.$page->ID.'.';
		exit();
	}

	/**
	 * @return boolean whether the user is authenticated
	 */
	public function isAuthenticated()
	{
		return $this->authenticated;
	}

	/**
	 * Sets the value indicating whether the user is authenticated.
	 * @param boolean whether the user is authenticated.
	 */
	public function setAuthenticated($authenticated)
	{
		$this->authenticated=$authenticated;
	}

	/**
	 * @return string the username of TUser
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Sets the username of TUser object
	 * @param string the username
	 */
	public function setUsername($username)
	{
		$this->username=$username;
	}

	/**
	 * Authenticates a user by his/her username and password.
	 * Default implementation simply saves the username and sets authentication true.
	 * Derived classes should override this method to do real authentication work
	 * such as check the username/password against DB.
	 * @param string the username
	 * @param string the password
	 * @return boolean if authentication succeeds
	 */
	public function login($username,$password)
	{
		$this->setUsername($username);
		$this->setAuthenticated(true);
		return true;
	}

	/**
	 * Sets authentication false for the user.
	 * Default implementation will destroy all session data related to the user visit.
	 * Derived classes may override this method to provide special treatment.
	 */
	public function logout()
	{
		$this->setAuthenticated(false);
		$this->setUsername('');
		$session=pradoGetApplication()->getSession();
		$session->destroy();
	}
}
?>