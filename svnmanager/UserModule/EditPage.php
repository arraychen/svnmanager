<?php

class EditPage extends TPage
{
	
	public function onInit($param)
	{
		parent::onInit($param);
		
		$userid = $_GET['UserID'];
		
		if(!$this->User->isAdmin() && $userid!=$this->User->getId())
		{
			echo "Not enough rights to change this user!";
			exit(-1);
		}
				
		$s_userid = $this->Module->Database->qstr($userid);
		$results = $this->Module->Database->Execute("SELECT * FROM users WHERE id=" . $s_userid);
		$fields = $results->fields;
		
		$user = array(
			'id'     => $fields['id'],
			'name'   => $fields['name'],
			'email'  => $fields['email'],
			'admin'  => $fields['admin'],
			'grants' => $fields['repositorygrants']
		); 
		
		$this->setSelectedUser($user);
		
		$this->UserID->setText($fields['id']);
		$this->UserName->setText($fields['name']);
		$this->Email->setText($fields['email']);
		if($fields['admin']==255)
			$this->Admin->setChecked(true);
		else
			$this->Admin->setChecked(false);
		$this->Grants->setText($fields['repositorygrants']);
		if(!$this->User->isAdmin())
		{
			$this->Grants->setEnabled(false);
			$this->Admin->setEnabled(false);
		}
		else
		{
			$this->Grants->setEnabled(true);
			$this->Admin->setEnabled(true);
		}					
	}	

	function getSelectedUser()
	{		
		return $this->getViewState('SelectedUser','');			
	}

	function setSelectedUser($value)
	{		
		$this->setViewState('SelectedUser',$value,'');			
	}

	function isSelectedUserAdmin()
	{
		$user = $this->getSelectedUser();
		return $user['admin']==255;
	}

	function onRequirePassword($sender, $param)
	{		
		$pw = $this->Password->getText();
		$id = $this->User->getId();
		if($this->Module->checkPassword($id, $pw))
			$param->isValid=true;
		else
			$param->isValid=false;			
		return;
	}		
		
	
	public function onCheckUserName($sender, $param)
	{
		$user = $this->getSelectedUser();
		$oldname = $user['name'];
		$newname = $this->UserName->getText();
		
		if($oldname==$newname)
		{
			$param->isValid=true;
			return;			
		}
		
		if($this->Module->isUsernameTaken($newname))
			$param->isValid=false;
		else
			$param->isValid=true;
			
		return;
	}
	
	public function onConfirmBtn($sender, $param)
	{

		if($this->IsValid)
		{
			$user = $this->getSelectedUser();
			$newuser = $this->getSelectedUser();
	
			$changes=false;
			
			if(strtolower($this->UserName->getText())!=$user['name'])
			{
			 	$changes=true;
				 $newuser['name']=strtolower($this->UserName->getText());
			}

			if(strtolower($this->Email->getText())!=$user['email'])
			{ 
				$changes=true;
				$newuser['email']=strtolower($this->Email->getText());
			}

			if($this->User->isAdmin())
			{
				//Admin checkbox changed?		
				if($user['admin']==255)
					if($this->Admin->Checked==false)
					{
						$changes=true;
						$newuser['admin']=0;
					}
				if($user['admin']!=255)
				if($this->Admin->Checked==true)
				{
					$changes=true;
					$newuser['admin']=255;
				}
				
				//Nr of grants changed?
				if($this->Grants->getText()!=$user['grants'])
				{
					$changes=true;
					$newuser['grants']=$this->Grants->getText();
				}									
			}
		
			//Password changed?
			if( $this->NewPassword->getText()!="" ) $changes=true;
			
	
			//Check for changed passwords, if so: update password
			if($this->NewPassword->getText()!="") 				
			{	
				$this->Module->updatePassword($user['id'], $this->NewPassword->getText());
			}
		
			$this->Module->updateAccount($user['id'], $newuser['name'], $newuser['email'], $newuser['admin'], $newuser['grants']);

			$this->EditPanel->setVisible(false);
			$this->ConfirmationPanel->setVisible(true);			
		}
	}

	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer('User:AdminPage');
	}	

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("User:AdminPage");
	}
	
}

?>
