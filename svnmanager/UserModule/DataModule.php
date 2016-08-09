<?php
/*
 * Created on 17-Jan-2005
 *
 */

using('System.Data');

class DataModule extends TModule
{
	public function onLoad($param)
	{
		parent::onLoad($param);

		require("config.php");
		
		//Set UTF-8 encoding
		mb_internal_encoding('UTF-8');
		
		$this->Database->setDataSourceName($dsn);
	}
	
	public function loadAccount($name='')
	{
		if(empty($name))
			$name=$this->User->getUsername();
		
		$s_username = $this->Database->qstr($username);
		$result=$this->Database->Execute("SELECT * FROM user WHERE name=" . $s_username);
		if($result->RecordCount()>0)
			return $result->fields;
		else
			return null;
	}

	public function createAccount($name, $password, $email, $admin, $repositorygrants)
	{
		require("config.php");				

		//Add user to svn password file
		//Escape special strings in htpasswd command
		$a_password = escapeshellarg($password);
		$a_name = escapeshellarg($name);

		if(!file_exists("$svn_passwd_file"))		
		{
			exec("$htpassword_cmd -cmb $svn_passwd_file $a_name $a_password"); 
		} else {
			exec("$htpassword_cmd -bm $svn_passwd_file $a_name $a_password"); 
		}
		
		$md5_pw = md5($password);
		

		$s_name = $this->Database->qstr($name);
		$s_email = $this->Database->qstr($email);
		$s_admin = $this->Database->qstr($admin);
		$s_repositorygrants = $this->Database->qstr($repositorygrants);
		
		$result = $this->Database->Execute("INSERT INTO users (id, name, password, email, admin, repositorygrants) VALUES (null, $s_name, '$md5_pw', $s_email, $s_admin, $s_repositorygrants)");

        $result = $this->Database->Execute("SELECT id FROM users WHERE name=$s_name");
        $id = $result->fields['id'];

		//If svnserve_user_file is specified, store passwords in rot13 and rebuild config file 
		if($svnserve_user_file != '') {
			$svn_pw = str_rot13($password);
	        $this->Database->Execute("INSERT INTO svnserve_pwd (ownerid, password) VALUES  ('$id', '$svn_pw')");
			$this->rebuildSVNServefile();
		}
		
	}

	public function updateAccount($id, $name, $email, $admin, $repositorygrants)
	{		
		require("config.php");

		$s_id = $this->Database->qstr($id);
		$s_name = $this->Database->qstr($name);
		$s_email = $this->Database->qstr($email);
		$s_admin = $this->Database->qstr($admin);
		$s_repositorygrants = $this->Database->qstr($repositorygrants);

		//Check if name is updated (in this case we need to update passwd files)
		$result = $this->Database->Execute("SELECT name FROM users WHERE id=$s_id");
		$oldname = strtolower($result->fields['name']);
		
		if(strtolower($name)!=$oldname) 
		{
			$old = '/'.$oldname.':/';
			$new = $name.':';

			$file = file_get_contents($svn_passwd_file);
			
			$buffer = preg_replace($old, $new, $file);
			
			if($buffer===$file) die("updateAccount: Something wrong updating passwd file!");
			
			file_put_contents($svn_passwd_file, $buffer);

		}
		
		$this->Database->Execute("UPDATE users SET name=$s_name, email=$s_email, admin=$s_admin, repositorygrants=$s_repositorygrants WHERE id=$s_id");
		
		if($svnserve_user_file != '') 
			$this->rebuildSVNServefile();
	}
	
	public function updatePassword($id, $password)
	{
		require("config.php");		

		$s_id = $this->Database->qstr($id);
		
		$results = $this->Database->Execute("SELECT name FROM users WHERE id=$s_id");
		$name = $results->fields['name'];

		//Escape special strings in htpasswd command 
		$a_password = escapeshellarg($password);
		$a_name = escapeshellarg($name);

		exec("$htpassword_cmd -mb $svn_passwd_file $a_name $a_password"); 
		
		$md5_pw = md5($password);
		$this->Database->Execute("UPDATE users SET password='$md5_pw' WHERE id=$s_id");
    
		if($svnserve_user_file != '') {

			$svn_pw = str_rot13($password);

			/*
    	 	 * 2006-03-03 PDurden - See if user already has an entry 
    	 	 */
    		$result = $this->Database->Execute("SELECT * FROM svnserve_pwd WHERE ownerid=$s_id");
			if($result->RecordCount()>0)
    		{
				$this->Database->Execute("UPDATE svnserve_pwd SET password='$svn_pw' WHERE ownerid=$s_id");
    		}	
			else
    		{
				$this->Database->Execute("INSERT INTO svnserve_pwd (ownerid, password) VALUES  ($s_id, '$svn_pw')");
    		}    
    
			$this->rebuildSVNServefile();
		}
		return;
	}

	public function isUsernameTaken($name)
	{
		$s_name = $this->Database->qstr($name);
		$result=$this->Database->Execute("SELECT * FROM users WHERE name=" . $s_name);
		return $result->RecordCount()>0;
	}
	
	public function isEmailTaken($email)
	{
		$s_email = $this->Database->qstr($email);
		$result=$this->Database->Execute("SELECT * FROM users WHERE email=" . $s_email);
		return $result->RecordCount()>0;	
	}
	
	public function isValidTicket($ticket)
	{
		$s_ticket = $this->Database->qstr($ticket);
		$result=$this->Database->Execute("SELECT * FROM usertickets WHERE ticket=" . $s_ticket);
		return $result->RecordCount()>0;
	}
	
	public function hasTicket($email)
	{
		$s_email = $this->Database->qstr($email);
		$result=$this->Database->Execute("SELECT * FROM usertickets WHERE email=" . $s_email);
		return $result->RecordCount()>0;
	}
	
	public function createTicket($email, $ticket, $repos)
	{
		$s_email = $this->Database->qstr($email);
		$s_ticket = $this->Database->qstr($ticket);
		$s_repos = $this->Database->qstr($repos);
		$result = $this->Database->Execute("INSERT INTO usertickets (email, ticket, repositorygrants) VALUES ($s_email, $s_ticket, $s_repos)");
	}
	
	public function getTicket($ticket)
	{
		$s_ticket = $this->Database->qstr($ticket);
		$result = $this->Database->Execute("SELECT email, ticket, repositorygrants FROM usertickets WHERE ticket=" . $s_ticket);
		return $result->fields;		
	}
	
	public function removeTicket($ticket)
	{
		$s_ticket = $this->Database->qstr($ticket);
		$result = $this->Database->Execute("DELETE FROM usertickets WHERE ticket=" . $s_ticket);		
	}	
	public function getUsername($id)
	{
		$s_id = $this->Database->qstr($id);
		$user = $this->Database->Execute("SELECT * FROM users WHERE id=" . $s_id);
		if($user)
			return $user->fields['name'];
		else
			return null;
	}

	public function rebuildAccessFile()
	{
		require_once("./svnmanager/library/class.accessfile.php");
		$accessfile = new AccessFile();
		$accessfile->createFromDatabase();
	}

	function rebuildSVNServeFile()
	{
		require_once("./svnmanager/library/class.svnservefile.php");
		$accessfile = new SVNServeFile();
		$accessfile->createFromDatabase();
	}

 public function deleteUser($id) 
{ 
	require("config.php"); 

	$s_id = $this->Database->qstr($id);
 
	$admin_id = $this->User->getId();
	$s_admin_id = $this->Database->qstr($admin_id);
	
	//Change Groups 
	$this->Database->Execute("UPDATE groups SET adminid=$s_admin_id WHERE adminid=$s_id"); 
 
	//Change Repositories 
	$this->Database->Execute("UPDATE repositories SET ownerid=$s_admin_id WHERE ownerid=$s_id"); 
 
	//Remove user from group(s) 
	$this->Database->Execute("DELETE FROM usersgroups WHERE userid=$s_id"); 
 
	//Remove user's privileges
	$this->Database->Execute("DELETE FROM userprivileges WHERE userid=$s_id");
	
	//Klaus Drechsler, 12.08.2005: 
	//bugfix: select name was after the user has been deletetd 
	$results = $this->Database->Execute("SELECT name FROM users WHERE id=$s_id"); 
	$name = $results->fields['name']; 
 
	//Remove user 
	$this->Database->Execute("DELETE FROM users WHERE id=$s_id"); 

	//Remove  
	if($svnserve_user_file != '')
		$this->Database->Execute("DELETE FROM svnserve_pwd WHERE ownerid=$s_id"); 
 
	//$results = $this->Database->Execute("SELECT name FROM users WHERE id=$s_id"); 
	//$name = $results->fields['name']; 
	$a_name = escapeshellarg($name);
 
	exec("$htpassword_cmd -D $svn_passwd_file $a_name"); 
 
	$this->rebuildAccessfile();
	if($svnserve_user_file != '')
		$this->rebuildSVNServefile();
} 	
	public function checkPassword($userid, $password)
	{
		//Check if admin is config file defined administrator (id=0)
		if($userid=='0')
		{
			require("config.php");
			if($password==$admin_temp_password)
				return true;
			else 
				return false;
		}

		$s_userid = $this->Database->qstr($userid);
		
		$md5_pw = md5($password);
		$result = $this->Module->Database->Execute("SELECT * FROM users WHERE id=$s_userid AND password='$md5_pw'");
		return $result->RecordCount()>0;
	
	}
		
	
	
}

?>
