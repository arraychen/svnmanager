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
		
		//This should prevent te removal of multibyte unicode characters with escapeshellarg
		if (false == setlocale(LC_CTYPE, "UTF8", $lang)) {
			die("skip setlocale() failed, unicode characters might fail!\n");
		}
		
		$this->Database->setDataSourceName($dsn);
	}
	
	public function hasGrants()
	{
		$userid = $this->User->getId();
		$s_userid = $this->Database->qstr($userid);
		
		$result = $this->Database->Execute("SELECT * FROM users WHERE id=$s_userid");
		$fields = $result->fields;
		
		if($fields['repositorygrants']>0)
			return true;
		
		if($this->User->isAdmin())
			return true;
		
		return false; 		
	}
	
	public function isTaken($name)
	{
		$s_name = $this->Database->qstr($name);
		$result = $this->Database->Execute("SELECT * FROM repositories WHERE name=" . $s_name);
		return $result->RecordCount()>0;
	}

	public function ownsRepositories()
	{
		$userid = $this->User->getId();
		$s_userid = $this->Database->qstr($userid);
		$result = $this->Database->Execute("SELECT * FROM repositories WHERE ownerid=" . $s_userid);
		return $result->RecordCount()>0;
	}
	
	public function createRepository($userid, $name, $description)
	{
		require "config.php";
				
		$rname = strtolower($name);
		$a_dir = $svn_repos_loc.DIRECTORY_SEPARATOR.escapeshellarg($rname);
		
		if(file_exists($a_dir))
		{
			echo("Can't make repository, $dir already exists.'");
			exit(-1);
		} else {
			//mkdir($dir);
			$ret = exec("LANG=".$lang.";$svnadmin_cmd --config-dir $svn_config_dir create $a_dir");

			if($ret!="") {
				error_log("svnadmin failed: $ret");
				return false;
			}

			$s_rname = $this->Database->qstr($rname);
			$s_userid = $this->Database->qstr($userid);
			$s_description = $this->Database->qstr($description);
						
			//Add repository into database
			$result = $this->Database->Execute("INSERT INTO repositories (id, name, ownerid) VALUES (null, $s_rname, $s_userid)");
            
			// Get the ID of the new repo
            $result = $this->Database->Execute("SELECT id FROM repositories WHERE name=$s_rname");

      		// Get the new repo's ID
      		$repoID = $result->fields['id'];

            // Now insert the rpo description
      		$result = $this->Database->Execute("INSERT INTO repo_descriptions (id, repo_id, description) VALUES (null, '$repoID', $s_description)");
      
			//Rebuild accessfile
			$this->rebuildAccessFile();		

      		// 2006-03-28 PDurden - If $post_create_script is set, then we need to
      		//    execute the script passing the new repository's location as the
      		//    one and only parameter
			if (isset($post_create_script))
      		{
        		$ret = exec($post_create_script." ".$a_dir);
      		}
		}
		
		return true;
		
	}
	
	public function deleteRepository($id)
	{				
		require("config.php");

		$s_id = $this->Database->qstr($id);
		
		//File removal
		$result = $this->Database->Execute("SELECT name FROM repositories WHERE id=$s_id");
		$name = $result->fields['name'];				
	
		$arg_repo_path = escapeshellarg($svn_repos_loc.DIRECTORY_SEPARATOR.$name);
		error_log("arg_repo_path:$arg_repo_path");
		
		if ($svn_trash_loc == '') {

			// Delete the repository directory.
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	 			$ret = exec("rmdir /q /s $arg_repo_path");
	 			if($ret!="") {
	 				error_log("Removing failed: $ret");
	 				return false;
	 			}
			} else {
				$ret = exec("LANG=".$lang.";rm -rf $arg_repo_path 2>&1");
				if($ret!="") {
	 				error_log("Removing failed: $ret");
	 				return false;
	 			}
			}
		} else {	// $svn_trash_loc != ''

			// Move the repository to the trash directory.  Also, tack on the current
			// timestamp, in case a new repository with the same name is created and
			// subsequently removed.
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

				// Filenames cannot contain colons (:) in Windows.
				$timestamp = str_replace(':', '_', date("c"));
				
				$arg_trash_path = escapeshellarg($svn_trash_loc.DIRECTORY_SEPARATOR.$name."-".$timestamp);
	 			$ret = exec("move /y $arg_repo_path $arg_trash_path");
	 			if($ret!="") {
	 				error_log("Moving (deleting) failed: $ret");
	 				return false;
	 			}
			} else {
				$arg_trash_path = escapeshellarg($svn_trash_loc.DIRECTORY_SEPARATOR.$name."-".date("c"));
				$ret = exec("LANG=".$lang.";mv -f $arg_repo_path $arg_trash_path 2>&1");
	 			if($ret!="") {
	 				error_log("Moving (deleting) failed: $ret");
	 				return false;
	 			}

			}

		}
		
		//User privileges
		$result = $this->Database->Execute("DELETE FROM userprivileges WHERE repositoryid=$s_id");
		
		//Group privileges
		$result = $this->Database->Execute("DELETE FROM groupprivileges WHERE repositoryid=$s_id");
		
		//Descriptions 
		$result = $this->Database->Execute("DELETE FROM repo_descriptions WHERE repo_id=$s_id");

		//Database removal
		$result = $this->Database->Execute("DELETE FROM repositories WHERE id=$s_id");
		
		//Accessfile recreation
		$this->rebuildAccessFile();
		
		return true;
	}	
	
	public function renameRepository($id, $newname)
	{
		require("config.php");

		$s_id = $this->Database->qstr($id);
		$s_newname = $this->Database->qstr($newname);

		//Check if new name is unique; repos doesn't already exist
		$results = $this->Database->Execute("SELECT * FROM repositories WHERE name=$s_newname");
		if($results->RecordCount()>0 || file_exists($svn_repos_loc.DIRECTORY_SEPARATOR.$newname) )
		{
			error_log("Can't rename repository to already existing name!");
			exit(-1);
		}
				
		//Retrieve old name
		$results = $this->Module->Database->Execute("SELECT name FROM repositories WHERE id=$s_id");		
		$oldname = $results->fields['name'];
		
		//Rename folder
		if(!rename($svn_repos_loc.DIRECTORY_SEPARATOR.$oldname, $svn_repos_loc.DIRECTORY_SEPARATOR.$newname))
		{
			error_log("Error renaming repository!");
			exit(-1);
		}

		//Rename in database
		$this->Module->Database->Execute("UPDATE repositories SET name=$s_newname WHERE id=$s_id");
		
		$this->rebuildAccessFile();			
		
	}
	
	public function changeRepositoryOwner($reposid, $newownerid)
	{
		$s_newownerid = $this->Database->qstr($newownerid);
		$s_reposid = $this->Database->qstr($reposid);

		$this->Database->Execute("UPDATE repositories SET ownerid=$s_newownerid WHERE id=$s_reposid");

		$this->rebuildAccessFile();
	}
	
	public function getUsername($id)
	{
		//User 0 is the '*' referring to all users in the svn access file
		if($id==0) return "*"; 

		$s_id = $this->Database->qstr($id);
		$user = $this->Database->Execute("SELECT * FROM users WHERE id=" . $s_id);
		if($user)
			return $user->fields['name'];
		else
			return null;
	}

	public function getGroupname($id)
	{
		$s_id = $this->Database->qstr($id);
		$group = $this->Database->Execute("SELECT * FROM groups WHERE id=" . $s_id);

		if($group)
			return $group->fields['name'];
		else
			return null;
	}

	public function getUserId($name)
	{
		$s_name = $this->Database->qstr($name);
		$id = $this->Database->Execute("SELECT * FROM users WHERE name=" . $s_name);
		if($id)
			return $id->fields['id'];
		else
			return null;
	}

	public function getGroupId($name)
	{
		$s_name = $this->Database->qstr($name);
		$id = $this->Database->Execute("SELECT * FROM groups WHERE name=" . $s_name);
		if($id)
			return $id->fields['id'];
		else
			return null;
	}
		
	public function getRepositoryname($id)
	{
		$s_id = $this->Database->qstr($id);
		$repos = $this->Database->Execute("SELECT * FROM repositories WHERE id=" . $s_id);
		if($repos)
			return $repos->fields['name'];
		else
			return null;
	}

	public function removeUserPrivileges($id)
	{
		$s_id = $this->Database->qstr($id);
		$results = $this->Database->Execute("DELETE FROM userprivileges WHERE id=" . $s_id);
		$this->rebuildAccessFile();	
	}

	public function removeGroupPrivileges($id)
	{
		$s_id = $this->Database->qstr($id);
		$results = $this->Database->Execute("DELETE FROM groupprivileges WHERE id=" . $s_id);
		$this->rebuildAccessFile();	
	}

	public function addUserPrivileges($userid, $repositoryid, $path, $access)
	{
		$s_userid = $this->Database->qstr($userid);
		$s_repositoryid = $this->Database->qstr($repositoryid);
		$s_path = $this->Database->qstr($path);
		$s_access = $this->Database->qstr($access);

		$results = $this->Database->Execute("INSERT INTO userprivileges (id, userid, repositoryid, path, access) VALUES (null, $s_userid, $s_repositoryid, $s_path, $s_access)");
		$this->rebuildAccessFile();
		
	}	

	public function addGroupPrivileges($groupid, $repositoryid, $path, $access)
	{
		$s_groupid = $this->Database->qstr($groupid);
		$s_repositoryid = $this->Database->qstr($repositoryid);
		$s_path = $this->Database->qstr($path);
		$s_access = $this->Database->qstr($access);

		$results = $this->Database->Execute("INSERT INTO groupprivileges (id, groupid, repositoryid, path, access) VALUES (null, $s_groupid, $s_repositoryid, $s_path, $s_access)");
		$this->rebuildAccessFile();
		
	}	

	/*
	 * Access:
	 * 0 = no access
	 * 1 = r 
	 * 2 = w 
	 * 3 = rw
	 */
	
	function rebuildAccessFile()
	{
		require_once("./svnmanager/library/class.accessfile.php");
		$accessfile = new AccessFile();
		$accessfile->createFromDatabase();
	}
	
	function updateGrants($userid, $nr)
	{
		$s_userid = $this->Database->qstr($userid);
		$s_nr = $this->Database->qstr($nr);

		$this->Module->Database->Execute("UPDATE users SET repositorygrants=$s_nr WHERE id=$s_userid");			
	}
	
	function getGrants($userid)
	{
		$s_userid = $this->Database->qstr($userid);
		$result = $this->Module->Database->Execute("SELECT repositorygrants FROM users WHERE id=" . $s_userid);
		return $result->fields['repositorygrants'];
	}

	public function repositoryExists($name)
	{
		$results = $this->Database->Execute("SELECT name FROM repositories");
	    if ($results)
	    {
	      while(!$results->EOF)
	      {
    	    $fields = $results->fields;
	        if (0 == strcmp($fields['name'], $name))
	        {
	          $results->Close();
    	      return true;
	        }
	        $results->MoveNext();
	      }			
	      $results->Close();
    	}
	    return false;
	}
	
}

?>
