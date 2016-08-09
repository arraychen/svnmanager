<?php

class RemovePage extends TPage
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
        		$repoID = $fields['id'];
        		
        		$s_repoID = $this->Module->Database->qstr($repoID);
        		$descrResults = $this->Module->Database->Execute("SELECT * FROM repo_descriptions WHERE repo_id=" . $s_repoID);
        		$descrFields = $descrResults->fields;
        		if ($descrFields['id'])
        		{
          			$description = wordwrap(htmlspecialchars($descrFields['description']), 40, "<br />\n");
          			//$description = $descrFields['description'];
        		}
        		else
		        {
          			$description = "No description for " . $fields['name'] . " repository";
        		}
				$data[] = array(
					'id' => $fields['id'],
					'repositoryname' => $fields['name'],
					'owner' => $owner,
      				'description' => $description
				);
			
				$results->MoveNext();
				$this->RepositoryTable->setDataSource($data);				
			}			
			$results->Close();			
		}	

		$this->dataBind();		
	}
	
	public function onDeleteRepository($sender, $param)
	{
		$id = $param->parameter;

		if(!$this->Module->deleteRepository($id)) {
			$this->FailedPanel->setVisible(true);
			$this->RemovePanel->setVisible(false);
			return;
		}
		
		//Increase the number of repositorygrants of this (normal) user
		if(!$this->User->isAdmin())
		{
			$userid = $this->User->getId();
			$grants = $this->Module->getGrants($userid);
			$grants++;
			$this->Module->updateGrants($userid, $grants);
		}
		
		$this->RemovePanel->setVisible(false);
		$this->ConfirmationPanel->setVisible(true);						
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
