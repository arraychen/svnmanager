<?php

class GroupPrivilegesPage extends TPage
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
		$id = $param->parameter;		
		$this->Application->transfer('Repository:GroupPrivilegesEditPage', array('RepositoryID' => $id));
								
	}

	public function onCancelBtn($sender, $param)
	{				
		$this->Application->transfer('Repository:AdminPage');							
	}

	
}
