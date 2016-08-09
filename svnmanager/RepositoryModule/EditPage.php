<?php

class EditPage extends TPage
{
	
	public function onInit($param)
	{
		parent::onInit($param);
		
		$repositoryid = $_GET['RepositoryID'];
		$s_repositoryid = $this->Module->Database->qstr($repositoryid);
		
		$results = $this->Module->Database->Execute("SELECT * FROM repositories WHERE id=$s_repositoryid");
		$fields = $results->fields;
		$ownerid = $fields['ownerid'];
		
		if(!$this->User->isAdmin() && $this->User->getId()!=$ownerid)
		{
			echo "Not enough rights to change this repository!";
			exit(-1);
		}

		$ownername = $this->Module->getUserName($ownerid);
		$results = $this->Module->Database->Execute("SELECT * FROM repo_descriptions WHERE repo_id=$s_repositoryid");
    	$description = $results->fields['description'];
    	$descrID = $results->fields['id'];
    
		$repository = array(
			'id' => $fields['id'],
			'name' => $fields['name'],
			'ownerid' => $ownerid,
			'ownername' => $ownername,
      		'description' => $description,
      		'descrID' => $descrID,
		);
		
		$this->setSelectedRepository($repository);
		
		$this->Name->setText($fields['name']);
 	    $this->Description->setText($description);
		
		$uresults = $this->Module->Database->Execute("SELECT name FROM users ORDER BY name");
		
		$users = array();
		while(!$uresults->EOF)
		{
			$users[] = $uresults->fields['name']; 
			$uresults->MoveNext();
		}
		$uresults->Close();
		sort($users);
		
		$this->Owner->setDataSource($users);
		$this->dataBind();
		
		$items=$this->Owner->Items;
		foreach($items as $item)
			if($item->getText()==$ownername)
				$item->setSelected(true);
			else
				$item->setSelected(false);

	}

	public function isValidName($sender, $param)
	{
		$repository = $this->getSelectedRepository();
		$name = $this->Name->getText();
		
		$param->isValid=false;
			
		if($name == $repository['name'])
			$param->isValid=true;
		else
		{
			$s_name = $this->Module->Database->qstr($name);
			$results = $this->Module->Database->Execute("SELECT * FROM repositories WHERE name=" . $s_name);
			if($results->RecordCount()==0)
				$param->isValid=true;		
		}
		return;
	}	
	
	public function setSelectedRepository($repos)
	{
		$this->setViewState('SelectedRepository', $repos, '');
	}
	
	public function getSelectedRepository()
	{
		return $this->getViewState('SelectedRepository', '');
	}
	
	public function onConfirmButton($sender, $param)
	{
		if($this->IsValid)
		{
			$changes=false;
			$repository=$this->getSelectedRepository();
			
			if($this->Name->getText()!=$repository['name'])
			{
				$this->Module->renameRepository($repository['id'], $this->Name->getText());
			}
      
      if ($this->Description->getText() != $repository['description'])
      {
        $newDescr = $this->Description->getText();
        $repoID = $repository['id'];
        $descrID = $repository['descrID'];
				$s_newDescr = $this->Module->Database->qstr($newDescr);
				$s_descrID = $this->Module->Database->qstr($descrID);
				$s_repoID = $this->Module->Database->qstr($repoID);
        if ($descrID) 
        {
          $this->Module->Database->Execute("UPDATE repo_descriptions SET description=$s_newDescr WHERE id=$s_descrID");
        }
        else
        {
          $this->Module->Database->Execute("INSERT INTO repo_descriptions (id, repo_id, description) VALUES (null, $s_repoID, $s_newDescr)");
        }
        
      }
						
			if($this->Owner->getSelectedItem()->getText()!=$repository['ownername'])
			{
				$newname = $this->Owner->getSelectedItem()->getText();
				
				$s_newname = $this->Module->Database->qstr($newname);
				$results = $this->Module->Database->Execute("SELECT id FROM users WHERE name=" . $s_newname);
				$newownerid = $results->fields['id'];
				$results->Close();				
				
				$this->Module->changeRepositoryOwner($repository['id'], $newownerid);
			}
		
			//$this->Application->transfer('Repository:AdminPage');
			$this->EditPanel->setVisible(false);
			$this->ConfirmationPanel->setVisible(true);							
		}
	}
	public function onCancelButton($sender, $param)
	{		
		$this->Application->transfer('Repository:AdminPage');
	}	

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}	
		
}
