<?php

require_once('svnmanager/global/AdminPageBase.php');

/*
 * Created on 31-Jan-2005
 *
 */
class CreatePage extends AdminPageBase
{
	public function onInit($param)
	{
		parent::onInit($param);
	}

	public function onLoad($param)
	{
		parent::onLoad($param);		
	}

	public function isNotTaken($sender, $param)
	{
		$name = $this->Name->getText();
		$param->isValid=!$this->Module->isTaken($name);
	}

	public function onConfirmBtn($sender, $param)
	{
		if($this->IsValid)
		{
			$name = $this->Name->Text;
			$groupid = $this->Module->createGroup($name );			
			//$this->Application->transfer("Group:AdminPage");
			$this->CreatePanel->setVisible(false);
			$this->ConfirmationPanel->setVisible(true);
			
			$this->setViewState('GroupID', $groupid, '');	
		}

	}
	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer("Group:AdminPage");
	}

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Group:AdminPage");
	}	
	
	public function onEdit($sender, $param)
	{
		$groupid = $this->getViewState('GroupID', '');
		$this->Application->transfer('Group:EditPage', array('GroupID' => $groupid) );
	}		
}
?>
