<?php

class AccessFile 
{

	private $database;
	
	function __construct() 
	{
		require("config.php");		
		$this->database = new TAdodb;
		$this->database->setDataSourceName($dsn);		
	}
	
	private function getUserName($userid)
	{
		//User 0 is the '*' referring to all users in the svn access file
		if($userid==0) return "*"; 
		
		$s_userid = $this->database->qstr($userid);
		$user = $this->database->Execute("SELECT * FROM users WHERE id=" . $s_userid);
		
		if($user)
			return $user->fields['name'];
		else
			return null;
	}

	/*
	 * Access:
	 * 0 = no access
	 * 1 = r 
	 * 2 = w 
	 * 3 = rw
	 */
	public function createFromDatabase() 
	{
		$accessfile = "";

		//Groups		
		$groups = $this->database->Execute("SELECT * FROM groups ORDER BY name");

		if ($groups) {
			$accessfile .= "[groups]\n";

			while (!$groups->EOF) {
				$groupname = $groups->fields['name'];
				$groupid = $groups->fields['id'];

				$test = $this->database->Execute("SELECT * FROM usersgroups");

				$s_groupid = $this->database->qstr($groupid);
				$usergroups = $this->database->Execute("SELECT * FROM usersgroups WHERE groupid=" . $s_groupid);

				if ($usergroups) {

					$accessfile .= "$groupname = ";
					$first = true;
					while (!$usergroups->EOF) {
						$userid = $usergroups->fields['userid'];

						$username = $this->getUserName($userid);

						if ($username == null) {
							error_log("Database inconsistent, can't find user that exists in group!'");
							exit (-1);
						}

						if ($first) {
							$first = false;
						} else {
							$accessfile .= ", ";
						}

						$accessfile .= "$username";
						$usergroups->MoveNext();
					}
					$usergroups->Close();
					$accessfile .= "\n";
				}

				$groups->MoveNext();
			}
			$groups->Close();
		}

		$accessfile .= "\n";

		//Access		
		$repositories = $this->database->Execute("SELECT * FROM repositories");
		$privhash = array();
		
		while (!$repositories->EOF) {
			$repositoryname = $repositories->fields['name'];
			$repositoryid = $repositories->fields['id'];
			$s_repositoryid = $this->database->qstr($repositoryid);
			$ownerid = $repositories->fields['ownerid'];
			$ownername = $this->getUserName($ownerid);

			if ($ownername == null) {
				error_log("Database inconsistent, can't find user that owns repository!'");
				exit (-1);
			}
			
			// Create hash to store privilegies for this repository
			$privhash[$repositoryname] = array();
			
			// Give owner full access to whole repository
			$privhash[$repositoryname]['/'] = "$ownername = rw\n";

			//User privileges
			$userprivileges = $this->database->Execute("SELECT * FROM userprivileges WHERE repositoryid=$s_repositoryid");
			if ($userprivileges)
				while (!$userprivileges->EOF) {
					$username = $this->getUsername($userprivileges->fields['userid']);
					if ($username == null) {
						error_log("Database inconsistent, can't find user that has privilege'");
						exit;
					}
					$access = $userprivileges->fields['access'];
					$path = $userprivileges->fields['path'];

					$useraccess = "$username = ";
					switch ($access) {
						case 0 :
							break;
						case 1 :
							$useraccess .= "r";
							break;
						case 2 :
							$useraccess .= "w";
							break;
						case 3 :
							$useraccess .= "rw";
							break;
					}
					$useraccess .= "\n";
					
					// Create string for path if it is not created yet
					if ( !isset($privhash[$repositoryname][$path]) ) { $privhash[$repositoryname][$path] = ""; }
					
					// Add user access to this path string
					$privhash[$repositoryname][$path] .= $useraccess;
					
					$userprivileges->MoveNext();
				}
			$userprivileges->Close();

			//Group privileges
			$groupprivileges = $this->database->Execute("SELECT * FROM groupprivileges WHERE repositoryid=$s_repositoryid");
			if ($groupprivileges)
				while (!$groupprivileges->EOF) {
					$groupid = $groupprivileges->fields['groupid'];

					$s_groupid = $this->database->qstr($groupid);
					
					$group = $this->database->Execute("SELECT * FROM groups WHERE id=" . $s_groupid);
					if (!$group) {
						error_log("Database inconsistent, can't find group that has privilege'");
						exit;
					}
					$groupname = $group->fields['name'];
					$access = $groupprivileges->fields['access'];
					$path = $groupprivileges->fields['path'];

					//$currUrl = "$repositoryname:$path";
					//if( $currUrl != $lastUrl ) { $accessfile .= "[$repositoryname:$path]\n"; $lastUrl=$currUrl; }
					$groupaccess = "@$groupname = ";
					switch ($access) {
						case 0 :
							break;
						case 1 :
							$groupaccess .= "r";
							break;
						case 2 :
							$groupaccess .= "w";
							break;
						case 3 :
							$groupaccess .= "rw";
							break;
					}
					$groupaccess .= "\n";

					// Create string for path if it is not created yet
					if ( !isset($privhash[$repositoryname][$path]) ) { $privhash[$repositoryname][$path] = ""; }
					
					// Add group access to this path string
					$privhash[$repositoryname][$path] .= $groupaccess;
					
					$groupprivileges->MoveNext();
				}
			$groupprivileges->Close();
			$repositories->MoveNext();
		}
		$repositories->Close();

		// Add access privilegies to accessfile string
		foreach ( $privhash as $repos => $reppaths ) {
			foreach ( $reppaths as $reppath => $pathaccess ) {
				$accessfile .= "[$repos:$reppath]\n";
				$accessfile .= $pathaccess;
			}
			$accessfile .= "\n";
		}
		
		require ("config.php");
		$filename = $svn_access_file;

		//	if (is_writable($filename)) 
		//	{
		if (!$handle = fopen($filename, 'w')) {
			echo "Cannot open file ($filename)";
			exit;
		}
		if (fwrite($handle, $accessfile) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}

		fclose($handle);
		//	} else {
		//		error_log("File not writable");
		//	}	

	}

}
