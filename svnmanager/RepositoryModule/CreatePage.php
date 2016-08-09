<?php
/*
 * Created on 31-Jan-2005
 *
 */
class CreatePage extends TPage
{
	public function onInit($param)
	{
		parent::onInit($param);

		// For non-admin users, make sure they are allowed to create
		// additional repositories.
		if (!$this->Module->hasGrants())
		{
			echo "You cannot create any additional repositories.";
			exit(-1);
		}
	}

	public function onLoad($param)
	{
		parent::onLoad($param);		
	}

	public function isNotTaken($sender, $param)
	{
		$name = strtolower( $this->Name->getText() );
		$param->isValid=!$this->Module->isTaken($name);
	}

	public function onConfirmBtn($sender, $param)
	{
		if($this->IsValid)
		{
			$name = strtolower(trim($this->Name->Text));
	        $description = $this->Description->Text;
			
			//Decrease the number of repositorygrants of this (normal) user
			if(!$this->User->isAdmin())
			{
				$userid = $this->User->getId();
				$grants = $this->Module->getGrants($userid);

				// Don't create the repository if they are out of grants.
				if ($grants <= 0)
				{
					echo "You cannot create any additional repositories.";
					exit(-1);
					return;
				}

				$grants--;
				$this->Module->updateGrants($userid, $grants);
			}
			
			if($this->Module->createRepository($this->User->getId(), $name, $description )) {
				$this->CreatePanel->setVisible(false);
				$this->ConfirmationPanel->setVisible(true);			
			} else {
				$this->CreatePanel->setVisible(false);
				$this->FailedPanel->setVisible(true);			
			}
		}

	}
	
	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}	
	

}
?>
