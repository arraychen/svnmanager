<?php

require_once('svnmanager/global/AdminPageBase.php');

class RemovePage extends AdminPageBase
{
	
	public function onLoad($param)
	{
		parent::onLoad($param);
		
		//Fill TRepeater with user names		
		$results=$this->Module->Database->Execute("SELECT * FROM users ORDER BY name");		
		if($results)
		{
			$data = array();
			while(!$results->EOF)
			{												
				$fields = $results->fields;
				//Skip current user
				if($fields['id']!=$this->User->getId())
				{
					$data[] = array(
						'userid' => $fields['id'],
						'username' => $fields['name'],
						'email' => $fields['email']
					);
				}
				$results->MoveNext();
				$this->UserTable->setDataSource($data);				
			}			
			$results->Close();			
		}	

		$this->dataBind();		
	}
	
	public function onDeleteUser($sender, $param)
	{
		$userid = $param->parameter;				
		$this->Module->deleteUser($userid);
		//$this->Application->transfer('User:AdminPage');
		$this->RemovePanel->setVisible(false);
		$this->ConfirmationPanel->setVisible(true);						
	}
	
	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer("User:AdminPage");
	}

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("User:AdminPage");
	}	
}
?>
