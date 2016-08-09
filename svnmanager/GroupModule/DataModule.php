<?php
/*
 * Created on 17-Jan-2005
 *
 * DataModule of groups, where all actual changes into database and
 * input file regarding Groups are done.
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

	public function getUsername($id)
	{
		$s_id = $this->Database->qstr($id);
		$user = $this->Database->Execute("SELECT * FROM users WHERE id=" . $s_id);
		if($user)
			return $user->fields['name'];
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
	
	public function removeUser($id, $userid)
	{
		$s_id = $this->Database->qstr($id);
		$s_userid = $this->Database->qstr($userid);
		$exists = $this->Database->Execute("SELECT * FROM usersgroups WHERE userid=$s_userid AND groupid=$s_id");
		if($exists->recordCount()>0)
		{
			$result = $this->Database->Execute("DELETE FROM usersgroups WHERE userid=$s_userid AND groupid=$s_id");
			$this->rebuildAccessFile();
		}
		$exists->Close();				
	}
	
	public function addUser($id, $userid)
	{
		$s_id = $this->Database->qstr($id);
		$s_userid = $this->Database->qstr($userid);
		$exists = $this->Database->Execute("SELECT * FROM usersgroups WHERE userid=$s_userid AND groupid=$s_id");
		if($exists->recordCount()==0)
		{
			$result = $this->Database->Execute("INSERT INTO usersgroups (userid, groupid) VALUES ($s_userid, $s_id)");
			$result->Close();
			$this->rebuildAccessFile();
		}
		$exists->Close();		
		
	}
	
	public function isTaken($name)
	{
		$s_name = $this->Database->qstr($name);
		$result = $this->Database->Execute("SELECT * FROM groups WHERE name=" . $s_name);
		return $result->RecordCount() > 0; 			
	}
	
	public function createGroup($name)
	{
		$userid = $this->User->getId();
		$s_name = $this->Database->qstr($name);
		$s_userid = $this->Database->qstr($userid);
		$result = $this->Database->Execute("INSERT INTO groups (id, name, adminid) VALUES (null, $s_name, $s_userid)");
		$groupid = $this->Database->Insert_ID();
		$result->Close();
		$this->rebuildAccessFile();		
		return $groupid;
	}
	
	public function deleteGroup($id)
	{
		//Delete access of group
		$s_id = $this->Database->qstr($id);
		$result = $this->Database->Execute("DELETE FROM groupprivileges WHERE groupid=$s_id");
		$result->Close();
		$result = $this->Database->Execute("DELETE FROM groups WHERE id=$s_id");
		$result->Close();
		$this->rebuildAccessFile();
	}
	
	public function renameGroup($id, $newname)
	{
		$s_id = $this->Database->qstr($id);
		$s_newname = $this->Database->qstr($newname);
		$result = $this->Database->Execute("UPDATE groups SET name=$s_newname WHERE id=$s_id");
		$result->Close();
		$this->rebuildAccessFile();
	}
	
	public function changeGroupOwner($id, $newownerid)
	{
		$s_id = $this->Database->qstr($id);
		$s_newownerid = $this->Database->qstr($newownerid);
		$result = $this->Database->Execute("UPDATE groups SET adminid=$s_newownerid WHERE id=$s_id");
		$result->Close();
		$this->rebuildAccessFile();
	}
	
	public function areGroups()
	{
		$result = $this->Database->Execute("SELECT * FROM groups");
		return $result->recordCount()>0;
	}

	function rebuildAccessFile()
	{
		require_once("./svnmanager/library/class.accessfile.php");
		$accessfile = new AccessFile();
		$accessfile->createFromDatabase();
	}
 
} 
?>
