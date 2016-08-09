<?php

class EditSelectPage extends TPage
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
	
	public function onSelectGroup($sender, $param)
	{
		$groupid = $param->parameter;
		$this->Application->transfer('Group:EditPage', array('GroupID' => $groupid) );		
	}

	public function onCancelBtn($sender, $param)
	{		
		$this->Application->transfer('Group:AdminPage');
	}	
	
}
?>
