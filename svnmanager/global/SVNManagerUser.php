<?php
/*
 * Created on 17-Jan-2005
 *
 */

class SVNManagerUser extends TUser
{
	//Code used from Prado's blog example

	private $userId='';
	private $userName='';
	private $userEmail='';
	private $redirectUrl='';
	private $isAdmin=false;				//Is superadmin
	private $ownsGroup=false;			//Admins any groups
	private $ownsRepository=false;		//Owns any repository
	private $configAdmin=false;			//True if this is administrator from config file
	
	public function setId($id)
	{
		$this->userId=$id;
	}

	public function getId()
	{
		return $this->userId;
	}
	
	public function setName($name)
	{
		$this->userName = $name;
	}
	
	public function getName()
	{
		return $this->userName;
	}

	public function setEmail($email)
	{
		$this->userEmail = $email;
	}
	
	public function getEmail()
	{
		return $this->userEmail;
	}

	public function setRedirectUrl($url)
	{		
		$this->redirectUrl=$url;
	}

	public function getRedirectUrl()
	{
		return $this->redirectUrl;
	}

	public function setAdmin($admin)
	{
		$this->isAdmin = $admin;
	}
	
	public function isAdmin()		
	{
		return $this->isAdmin;
	}
	
	public function setGroup($group)
	{
		$this->ownsGroup=$group;
	}

	public function ownsGroup()
	{
		return $this->ownsGroup;
	}

	public function setRepository($repos)
	{
		$this->ownsRepository=$repos;
	}

	public function ownsRepository()
	{
		return $this->ownsRepository;
	}
	
	public function setConfigAdmin($cadmin)
	{
		$this->configAdmin = $cadmin;
	}
	
	public function isConfigAdmin()
	{
		return $this->configAdmin;
	}
	
	public function onAuthenticationRequired($pageName)
	{
		$this->setRedirectUrl($_SERVER['REQUEST_URI']);
		pradoGetApplication()->transfer('User:LoginPage');
	}

	public function onAuthorizationRequired($page)
	{
		$this->onAuthenticationRequired($page->ID);
	}
	
	public function needsRepositoryMenu()
	{
		if($this->isConfigAdmin()) return false;
		if($this->isAdmin())return true;
		if($this->ownsRepository()) return true;
	
		//Has grants
		$adodb = new TAdodb;	
		require("config.php");

		$adodb->setDataSourceName($dsn);
		$adodb->SetFetchMode("Associative");
		$userid=$this->getId();

		$s_userid = $adodb->qstr($userid);
		
		$result = $adodb->Execute("SELECT * FROM users WHERE id=" . $s_userid);
		$fields = $result->fields;
		
		if($fields['repositorygrants']>0)
			return true;
			
		return false;
	}
	
	public function login( $name, $password = '')
	{
		$authenticated=false;

		$adodb = new TAdodb;

		//$adodb->setDataSourceName($this->Application->getUserParameter('DSN'));
		require("config.php");
		$adodb->setDataSourceName($dsn);

		$adodb->SetFetchMode("Associative");

		//Check if there are any admin users
		$result=$adodb->Execute("SELECT * FROM users WHERE admin=255");
		
		//Check with config.php password if there are no admin users
		if($result->RecordCount()==0)
		{
			if($name==$admin_name && $password==$admin_temp_password)
			{
				$this->setEmail("no@email.net");
				$this->setId(0);
				$this->setAdmin(true);
				$this->setGroup(false);
				$this->setRepository(false);
				$this->setAuthenticated(true);
				$this->setConfigAdmin(true);
				$result->Close();
				return true;				
			}
		}
		$result->Close();

		//Check for database user
		$md5_pw = md5($password);
		
		$s_name = $adodb->qstr($name);
		
		$result=$adodb->Execute("SELECT * FROM users WHERE name=$s_name AND password='$md5_pw'");
		//$result=$adodb->Execute("SELECT * FROM users WHERE name='$name' AND password = MD5('$password')");				
		if($result->RecordCount()>0)
		{						
			$authenticated = true;
			$fields = $result->fields; 				
			$userid = $fields['id'];
			$s_userid = $adodb->qstr($userid);

			$email = $fields['email'];
			
			$this->setEmail($email);
		
			$this->setId($userid);
		
			if($fields['admin']==255)		//Level 255 is superadmin
				$this->setAdmin(true);
			else
				$this->setAdmin(false);
			
			$groups = $adodb->Execute("SELECT * FROM groups WHERE adminid=$s_userid");
			if($groups->RecordCount()>0)
				$this->setGroup(true);
			else
				$this->setGroup(false);
				
			$repos = $adodb->Execute("SELECT * FROM repositories WHERE ownerid=$s_userid");
			if($repos->RecordCount()>0)
				$this->setRepository(true);
			else
			{
				$repos = $adodb->Execute("SELECT repositorygrants FROM users WHERE id=$s_userid");
				if($repos->fields['repositorygrants']>0)
					$this->setRepository(true);		 	
			 	else
					$this->setRepository(false);
			}	
		}
		$result->Close();
		$this->setAuthenticated($authenticated);
		return $authenticated;
	}
	
	public function logout()
	{
		parent::logout();
		$this->setAdmin(false);
		$this->setGroup(false);
		$this->setRepository(false);
	}	
}
?>
