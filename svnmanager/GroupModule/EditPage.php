<?php

class EditPage extends TPage
{
	
	public function onLoad($param)
	{
		parent::onLoad($param);
		
			$groupid = $_GET['GroupID'];
			$s_groupid = $this->Module->Database->qstr($groupid);
		
			$results = $this->Module->Database->Execute("SELECT * FROM groups WHERE id=$s_groupid");

			$fields = $results->fields;
			$adminid = $fields['adminid'];
		
			if(!$this->User->isAdmin() && $this->User->getId()!=$adminid)
			{
				echo "Not enough rights to change this group!";
				exit(-1);
			}
		
		if(!$this->isPostBack())
		{
			$name = $fields['name'];
	
			$members = array();			
			$membernames = array();
				
			$membresults = $this->Module->Database->Execute("SELECT * FROM usersgroups WHERE groupid=$s_groupid");
		
			while(!$membresults->EOF)
			{
				$fields = $membresults->fields;			
				$members[] = $fields['userid'];						
				$membernames[] = $this->Module->getUsername($fields['userid']);
				$membresults->MoveNext();
			}
			$membresults->Close();

			$group = array(
				'id' 			=> $groupid,
				'name'			=> $name,
				'adminid'		=> $adminid,
				'members'		=> $members,
				'membernames'	=> $membernames		
			);
		
			$this->setSelectedGroup($group);
		
			$uresults = $this->Module->Database->Execute("SELECT * FROM users ORDER BY name");
			$users = array();
			while(!$uresults->EOF)
			{
				$users[] = $uresults->fields['name']; 
				$uresults->MoveNext();
			}
			$uresults->Close();
			sort($users);				
				
			$this->Owner->setDataSource($users);						
			$this->Members->setDataSource($users);					
			$this->dataBind();
		
			$this->Name->setText($name);
			
			$items=$this->Owner->Items;
			$ownername = $this->Module->getUsername($adminid);
			foreach($items as $item)
				if($item->getText()==$ownername)
					$item->setSelected(true);
				else
					$item->setSelected(false);
		
			$items=$this->Members->Items;
			foreach($items as $item)
				$item->Selected=(in_array($item->Text, $membernames));
		}
	}

	public function isValidName($sender, $param)
	{
		$group = $this->getSelectedGroup();
		$name = $this->Name->getText();
		
		$param->isValid=false;
			
		if($name == $group['name'])
			$param->isValid=true;
		else
		{
			$s_name=$this->Module->Database->qstr($name);
			$results = $this->Module->Database->Execute("SELECT * FROM groups WHERE name=" . $s_name);
			if($results->RecordCount()==0)
				$param->isValid=true;		
		}
		return;
	}	
	
	public function onConfirmButton($sender, $param)
	{		
		if($this->IsValid)
		{
			$changes=false;
			$group=$this->getSelectedGroup();
			
			//Check if name is changed
			if($this->Name->getText()!=$group['name'])
			{
				$changes=true;
				$this->Module->renameGroup($group['id'], $this->Name->getText());
			}
			
			//Check if owner is changed
			if($this->Owner->getSelectedItem()->getText()!=$this->Module->getUsername($group['adminid']))
			{
				$newid = $this->Module->getUserId($this->Owner->getSelectedItem()->getText()); 				
				$this->Module->changeGroupOwner($group['id'], $newid);				
			}
			
			//Check if the members are changed
			$items=$this->Members->getItems();					
			foreach($items as $item)
			{	
				$mname = $item->getText();
			
				if($item->isSelected())
				{
					//New selected					 				
					if(!in_array($mname, $group['membernames']))
					{
						$changes=true;
						$id = $this->Module->getUserId($mname);
						$this->Module->addUser($group['id'], $id);						
					}
				} else {
					//New unselected
					if(in_array($mname, $group['membernames']))
					{
						$changes=true;
						$id = $this->Module->getUserId($mname);
						$this->Module->removeUser($group['id'], $id);
					}
				}
			}
			
			//$this->Application->transfer('Group:AdminPage');
			$this->ChangePanel->setVisible(false);
			$this->ConfirmationPanel->setVisible(true);			

		}
	}
	
	public function onCancelButton($sender, $param)
	{		
		$this->Application->transfer('Group:AdminPage');
	}
	
	public function setSelectedGroup($group)
	{
		$this->setViewState('SelectedGroup', $group, '');
	}

	public function getSelectedGroup()
	{
		return $this->getViewState('SelectedGroup', '');
	}
	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Group:AdminPage");
	}	
		
}		
