<?php
/*
 * Created on 20-Jan-2005
 *
 */
class ActivatePage extends TPage
{
	public function onInit($param)
	{
		parent::onInit($param);
		if(isset($_GET['ticket']))
		{
			$this->setTicket($_GET['ticket']);
		}	
		$this->dataBind();										
	}

	public function onLoad($param)
	{
		parent::onLoad($param);		
	}
	
	function getTicket()
	{		
		return $this->getViewState('Ticket','');			
	}
	function setTicket($value)
	{		
		$this->setViewState('Ticket',$value,'');			
	}

	function isValidTicket()
	{
		return $this->Module->isValidTicket($this->getTicket()); 
	}
	
	public function isUsernameTaken($sender,$param)
	{				
		$param->isValid=!$this->Module->isUsernameTaken(strtolower($this->Username->Text));
	}
	
	public function onConfirmBtn($sender, $param)
	{
		$ticket = $this->Module->getTicket($this->getTicket());				
		
		$username=strtolower($this->Username->Text);
		$password=$this->Password->Text;
		
		if($this->IsValid)
		{

			//Add user
			$this->Module->createAccount(	$username, 
											$password,
											$ticket['email'],
											0,							//No admin rights
											$ticket['repositorygrants']
										);

			//Remove ticket
			$this->Module->removeTicket($this->getTicket());

			//Login
			$this->User->login($username, $password);									
						
			$this->Application->Transfer("Main:StartPage");
	
		}
	}
}
?>
