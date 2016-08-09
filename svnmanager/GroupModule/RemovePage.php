<?php

class RemovePage extends TPage
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		$userid = $this->User->getId();
		
		if($this->User->IsAdmin())
			$results = $this->Module->Database->Execute("SELECT * FROM groups ORDER BY name");
		else {
			$s_userid = $this->Module->Database->qstr($userid);
			$results = $this->Module->Database->Execute("SELECT * FROM groups WHERE adminid=" . $s_userid);
		}		
		if($results)
		{
			$data = array();
			while(!$results->EOF)
			{
				$fields = $results->fields;
				$owner = $this->Module->getUserName($fields['adminid']);
				$data[] = array(
					'id' => $fields['id'],
					'groupname' => $fields['name'],
					'admin' => $owner
				);
			
				$results->MoveNext();
				$this->GroupTable->setDataSource($data);				
			}			
			$results->Close();			
		}	

		$this->dataBind();		
	}
	
	public function onDeleteGroup($sender, $param)
	{
		$id = $param->parameter;
		$this->Module->deleteGroup($id);
		
		$this->RemovePanel->setVisible(false);
		$this->ConfirmationPanel->setVisible(true);		
		
		//$this->Application->transfer('Repository:AdminPage');						
	}

	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer('Group:AdminPage');
	}

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Group:AdminPage");
	}	

}
?>
