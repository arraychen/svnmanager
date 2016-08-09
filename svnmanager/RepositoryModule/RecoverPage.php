<?php

class RecoverPage extends TPage
{
	
	public function onLoad($param)
	{
		parent::onLoad($param);
		$userid = $this->User->getId();
				
		if($this->User->IsAdmin())
			$results = $this->Module->Database->Execute("SELECT * FROM repositories ORDER BY name");
		else {
			$s_userid = $this->Module->Database->qstr($userid);			
			$results = $this->Module->Database->Execute("SELECT * FROM repositories WHERE ownerid=" . $s_userid);
		}	
			
		if($results)
		{
			$data = array();
			while(!$results->EOF)
			{
				$fields = $results->fields;
				$owner = $this->Module->getUserName($fields['ownerid']);
				$data[] = array(
					'id' => $fields['id'],
					'repositoryname' => $fields['name'],
					'owner' => $owner
				);
			
				$results->MoveNext();
				$this->RepositoryTable->setDataSource($data);				
			}			
			$results->Close();			
		}	

		$this->dataBind();		
	}
	
	public function onSelectRepository($sender, $param)
	{
		$repositoryid = $param->parameter;		
		//$this->Application->transfer('Repository:EditPage', array('RepositoryID' => $id));
		include("config.php");
						
		$s_repositoryid = $this->Module->Database->qstr($repositoryid);
		$results = $this->Module->Database->Execute("SELECT * FROM repositories WHERE id=" . $s_repositoryid);
		$fields = $results->fields;
		$ownerid = $fields['ownerid'];
		$name = $fields['name'];
		
		if(!$this->User->isAdmin() && $this->User->getId()!=$ownerid)
		{
			echo "Not enough rights to recover this repository!";
			exit(-1);
		}

		exec( $svnadmin_cmd." --config-dir $svn_config_dir recover ".escapeshellarg($svn_repos_loc.DIRECTORY_SEPARATOR.$name) );
		
		$this->TablePanel->setVisible(false);
		$this->MessageLabel->setText("Tried to recover Repository!");
		$this->ResultPanel->setVisible(true);		
		
	}

	public function onCancelBtn($sender, $param)
	{		
		$this->Application->transfer('Repository:AdminPage');
	}	
	
	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}	
	

}
?>
