<?php
/*
 * Created on 17-feb-2005
 *
 */

class EditSelectPage extends TPage
{

	public function onInit($param)
	{
		parent::onInit($param);
	
	}

	public function onLoad($param)
	{
		parent::onLoad($param);		

		if($this->User->isAdmin())
			$results=$this->Module->Database->Execute("SELECT * FROM users ORDER BY name");
		else
			$this->Application->transfer('User:EditPage', array('UserID' => $this->User->getID()));
		
		if($results)
		{
			$data = array();
			while(!$results->EOF)
			{
				$fields = $results->fields;
				
				$data[] = array(
					'userid' => $fields['id'],
					'username' => $fields['name'],
					'email' => $fields['email']
				);
			
				$results->MoveNext();							
			}			
			$results->Close();		
			$this->UserTable->setDataSource($data);		
		}	

		$this->dataBind();										

	}

	public function onUserSelected($sender, $param)	
	{
		$userid = $param->parameter;
		$this->Application->transfer('User:EditPage', array('UserID' => $userid) );		
	}

	public function onCancelBtn($sender, $param)	
	{		
		$this->Application->transfer('User:AdminPage');		
	}

}
?>
