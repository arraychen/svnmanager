<?php

require_once 'PEAR.php';
require_once 'PEAR/ErrorStack.php';
		
class UserPrivilegesEditPage extends TPage
{
	public function setSelectedRepository($repos)
	{
		$this->setViewState('SelectedRepository', $repos, '');
	}
	
	public function getSelectedRepository()
	{
		return $this->getViewState('SelectedRepository', '');
	}
	
	public function onInit($param)
	{
		parent::onInit($param);
			
		//Check the selected repository
		$repositoryid = $_GET['RepositoryID'];	
		
		$s_repositoryid = $this->Module->Database->qstr($repositoryid);
		$results = $this->Module->Database->Execute("SELECT * FROM repositories WHERE id=" . $s_repositoryid);
		$fields = $results->fields;
		$ownerid = $fields['ownerid'];
		
		if(!$this->User->isAdmin() && $this->User->getId()!=$ownerid)
		{
			echo "Not enough rights to change this repository!";
			exit(-1);
		}
		
		$this->setSelectedRepository($repositoryid);
		
		$ownername = $this->Module->getUserName($ownerid);
		$repositoryname = $this->Module->getRepositoryname($repositoryid); 

		$this->RepositoryName->setText($repositoryname);
		$this->RepositoryOwner->setText($ownername);
		
		//Reproduce dynamic buttons for proper event handling (something is not completely right, Prado!)
		$sess = $this->Application->getSession();
		if($sess->has("linkbuttons"))
		{
			$linkbuttons = $sess->get("linkbuttons");			
			foreach($linkbuttons as $lb)
			{
				$this->PathHolder->addChild($lb);
				$this->PathHolder->addBody($lb);
			}
			$sess->clear("linkbuttons");
		}
		
		if($sess->has("listbox"))
		{
			$listbox = $sess->get("listbox");
			$this->PathHolder->addChild($listbox);
			$this->PathHolder->addBody($listbox);
			$sess->clear("listbox");
		}
		
	}
	
	public function onLoad($param)
	{
		parent::onLoad($param);
		
		//Catch postback of path change events and handle them!
		if($this->IsPostBack)		
		{
  			$sender=$this->Page->getPostBackTarget();  			  			
  			
  			if($sender!=null)
  				$sid=$sender->getId();  			  			
  			else
  				$sid="AddPath";  				  			
  			
  			$path=$this->getViewState('Path','');
  			
  			if($sid=="AddPath")  			
  			{
				$path[]=$this->PathHolder->AddPath->getSelectedItem()->getText();// Value();				
  			} else 
  			if(strlen($sid)>3 && substr($sid,0,3)=="lev" )
  			{
  				$nlevel=$sender->getCommandParameter();
  				$newpath=array();
  				$newpath[]="/";
  				if($nlevel>1)
  					for($i=1;$i<$nlevel;$i++)
  						$newpath[]=$path[$i];
  				$path=$newpath;
  			}
  			$this->setViewState('Path', $path);
		} 

		//Make a list of defined rights
		$repositoryid = $_GET['RepositoryID'];
		$s_repositoryid = $this->Module->Database->qstr($repositoryid);	
		$results = $this->Module->Database->Execute("SELECT * FROM userprivileges WHERE repositoryid=" . $s_repositoryid . " ORDER BY path, userid");
		if($results->RecordCount()>0)
		{
			$data = array();
			while(!$results->EOF)
			{
				$fields = $results->fields;
				
				$id = $fields['id'];
				$username = $this->Module->getUsername($fields['userid']);
				$path = $fields['path'];

				/*
				 * Access:
				 * 0 = no access
				 * 1 = r 
				 * 2 = w 
				 * 3 = rw
	 			*/

				
				if($fields['access']==1 || $fields['access']==3 )
					$read="yes";
				else
					$read="no";
					
				if($fields['access']==2 || $fields['access']==3 )
					$write="yes";
				else
					$write="no";			
							
				$data[] = array(
					'id' => $id,
					'username' => $username,
					'path' => $path,
					'read' => $read,
					'write' => $write
				);				
				
				$results->MoveNext();				

			}
			$results->Close();
			$this->RightsTable->setDataSource($data);
			$this->RightsTable->dataBind();
		}
		
		//Make a list of Users
		if(!$this->IsPostBack)
		{
			$membernames = array();
			//$ownerid=
			$membresults = $this->Module->Database->Execute("SELECT * FROM users ORDER BY name");
		
			//General permissions
			$membernames[] = "*";
			while(!$membresults->EOF)			{															
				$membernames[] = $membresults->fields['name'];
				$membresults->MoveNext();
			}
			$membresults->Close();

			$this->UserSelector->setDataSource($membernames);
			$this->UserSelector->dataBind();
		}

		//Fix place holder
		$path = $this->getViewState('Path','');
		if(!isset($path)||$path=='') 
		{
			$path = array();
			$path[]="/";	
		}
		
		$this->setViewState('Path',$path);
		
		$this->PathHolder->removeBodies();
		$this->PathHolder->removeChildren();
		
		$sess = $this->Application->getSession();
		
		//Linkbuttons
		$linkbuttons = array();
		$level=0;
		foreach($path as $p)
		{
			$id="lev$level";
			$lb = $this->PathHolder->createComponent('TLinkButton',$id);
			$lb->setText($p);
			$lb->setCommandName("path");
			$lb->setCommandParameter($level);
			$linkbuttons[]=$lb; 
			if($level>1)$this->PathHolder->addBody("/");
			$this->PathHolder->addBody($lb);
			$level++;			
		}
		
		$sess->set("linkbuttons", $linkbuttons);
		
		//retrieve sub paths from repository
		require_once("VersionControl/SVN.php");
		require("config.php");
		$svnstack = &PEAR_ErrorStack::singleton('VersionControl_SVN');

		// 2006-02-15 PDurden Check to see if this is a Windows installation
		//if (isset($is_windows) && $is_windows)
		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$svn_cmd = getenv("COMSPEC")." /C ".$svn_cmd;
		}

		// Set up runtime options. 
		$options = array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_ARRAY, 'svn_path' => $svn_cmd);
		//Request list class from factory
		$svn = VersionControl_SVN::factory(array('list'), $options);
		
		// 2006-02-15 PDurden Check to see if this is a Windows installation
		//if (isset($is_windows) && $is_windows)
		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$svn->use_escapeshellcmd = false;
		}
		
		$repositoryid = $_GET['RepositoryID'];
		$repositoryname = $this->Module->getRepositoryname($repositoryid);

		// 2006-02-15 PDurden Check to see if this is a Windows installation
		//if (isset($is_windows) && $is_windows)
		if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			// If leading / is missing, add the /
			if (substr($svn_repos_loc, 0, 1) != '/') $svn_repos_loc = '/'.$svn_repos_loc;
			// Replace all \ with /
			$svn_repos_loc = str_replace("\\", "/", $svn_repos_loc);  
		}

		$url = "file://$svn_repos_loc/$repositoryname";		

		if(sizeof($path)>1)
		{
			foreach($path as $p)$url.="/$p";
		}

		// 2006-02-16 PDurden - If there's a space in the URL, place it in quotes
		if (strstr($url, " "))
		{
			$url = "\"".$url."\"";
		}

		//$switches = array();
		$switches = array('config-dir' => $svn_config_dir); 		

		$args = array($url);
			
		$subfolders=array();		
		
		try {
			$output = $svn->list->run($args, $switches);
		} catch (VersionControl_SVN_Ezception $e) {
			print_r($e->getMessage());
		}

		if(isset($output['list']['0']['entry']))
		{
			$subfolders[]=" -choose- ";
			foreach($output['list']['0']['entry'] as $item)
			{				
				if(isset($item['kind']) && $item['kind']=='dir') $subfolders[]=$item['name'];
			}
		} 
				
		if(sizeof($subfolders)>1)
		{
			$listbox = $this->PathHolder->createComponent('TListBox','AddPath');
			$listbox->setDataSource($subfolders);
			$listbox->dataBind();
			$listbox->setRows(1);	
			$listbox->setAutoPostBack(true);		
			$listbox->setSelectedIndex(0); //Always start with first selected (empty);
			if($level>1)$this->PathHolder->addBody("/");
			$this->PathHolder->addBody($listbox);
			$sess->set("listbox", $listbox);
		} else {
			$sess->clear("listbox");
		}				

	}
	
	public function onPrerender($param)
	{
		parent::onPreRender($param);
		
		//If there's a listbox, select the first item
		$sess=$this->Application->getSession();
		if($sess->has("listbox"))
			$this->PathHolder->AddPath->setSelectedIndex(0);
		
	}
	
	public function onRemovePrivileges($sender, $param)
	{
		$id = $param->parameter;
		
		//Check if user may remove this privileges
		if(!$this->User->isAdmin())
		{
			$s_id = $this->Module->Database->qstr($id);
			$priv = $this->Module->Database->Execute("SELECT * FROM userprivileges WHERE id=" . $s_id);
			$reposid = $priv->fields['repositoryid'];
			$priv->Close();
			$s_reposid = $this->Module->Database->qstr($s_reposid);
			$repos = $this->Module->Database->Execute("SELECT * FROM repositories WHERE id=" . $s_reposid);
			$ownerid = $repos->fields['ownerid'];
			$repos->Close();
			if($this->User->getId()!=$ownerid)
			{
				echo "Not enough rights to change these rights!";
				exit(-1);
			}
		}
			
		$this->Module->removeUserPrivileges($id);
		$this->Application->transfer('Repository:UserPrivilegesEditPage', array('RepositoryID' => $this->getSelectedRepository() ));				
				
	}

	public function onClickAddBtn($sender, $param)
	{
		$repositoryid = $this->getSelectedRepository();
		$username = $this->UserSelector->getSelectedItem()->getText();		
		$userid = $this->Module->getUserId($username);
		$path = "";
			
		//Create selected path string
		$patha = $this->getViewState('Path','');
		$i=0;
		foreach($patha as $p)
		{
			if($i>1)$path.="/";
			$path.=$p;
			$i++;
		}
		
		/*
		 * Access:
		 * 0 = no access
		 * 1 = r 
		 * 2 = w 
		 * 3 = rw
	 	*/
	 	
	 	$access=0;
	 	if($this->Read->isChecked())$access+=1;
	 	if($this->Write->isChecked())$access+=2;
		
		$this->Module->addUserPrivileges($userid, $repositoryid, $path, $access);
		$this->Application->transfer('Repository:UserPrivilegesEditPage', array('RepositoryID' => $this->getSelectedRepository() ));
		
	}
	
	public function onClickDoneBtn($sender, $param)
	{
		$this->Application->transfer('Repository:AdminPage');
	}
		
}
?>
