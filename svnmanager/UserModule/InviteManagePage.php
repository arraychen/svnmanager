<?php
/*
** 2006-04-11 PDurden - Created this module for managing invitations
**
*/

class InviteManagePage extends TPage
{

	public function onInit($param)
	{
		parent::onInit($param);
	
	}

	public function onLoad($param)
	{
		parent::onLoad($param);		

		if($this->User->isAdmin())
			$results=$this->Module->Database->Execute("SELECT * FROM usertickets");
		else
			$this->Application->transfer('User:EditPage', array('UserID' => $this->User->getID()));
		
		if($results)
		{
			$data = array();
			while(!$results->EOF)
			{
				$fields = $results->fields;
				
				$data[] = array('email' => $fields['email']);
			
				$results->MoveNext();							
			}			
			$results->Close();		
			$this->UserTable->setDataSource($data);		
		}

		$this->dataBind();										

	}

	public function deleteInvite($sender, $param)	
	{
    $repeaterItem = $sender->Parent;
    $itemIndex = $repeaterItem->Index;
    
	$email = $this->UserTable->Items[$itemIndex]->emailField->Text;
	$s_email = $this->Module->Database->qstr($email);
    $this->Module->Database->Execute("DELETE FROM usertickets WHERE email=$s_email");
    $this->MainPanel->setVisible(false);
    $this->DeletePanel->setVisible(true);
	}

	public function sendInviteAgain($sender, $param)	
	{
    $repeaterItem = $sender->Parent;
    $itemIndex = $repeaterItem->Index;
    
	$email = $this->UserTable->Items[$itemIndex]->emailField->Text;
	$s_email = $this->Module->Database->qstr($email);

    $results = $this->Module->Database->Execute("SELECT * FROM usertickets WHERE email=$s_email");
		if ($results)
    {
      $fields = $results->fields;
      $email  = $fields['email'];
      $ticket = $fields['ticket'];
      
      require("config.php");
      require("./svnmanager/library/class.phpmailer.php");
  
      $servername =$_SERVER['SERVER_NAME'];
      $page = $_SERVER['PHP_SELF'];
  
      // 2006-03-28 PDurden Removed assumption of https protocol
      $port = $_SERVER['SERVER_PORT'];
      if ($port != '80')
      {
        if ($port == '443')
        {
          $url = "https://$servername$page?page=User:ActivatePage&ticket=$ticket";
        }
        else
        {
          $url = "http://$servername:$port$page?page=User:ActivatePage&ticket=$ticket";
        }
      }
      else
      {
        $url = "http://$servername$page?page=User:ActivatePage&ticket=$ticket";; 
      }
      
      $message = 
        "Your are invited to create an account on the $servername server. Please follow the link to activate your account:\n".
        "\n".
        $url."\n".
        "\n".
        "Regards";
      
      $mail = new PHPMailer();
      $mail->From     = "svnmanager@$servername";
      $mail->FromName = "SVNManager";
      $mail->Host     = "$smtp_server";
      $mail->Mailer   = "smtp";
      $mail->Body = $message;
      $mail->AddAddress($email);
      $mail->Subject = "Account Invitation (resend)";
  
      //Email invitation and Store ticket into database
      $this->MainPanel->setVisible(false);
      if ($mail->Send())
      {
        $this->SendAgainPanel->setVisible(true);
      }
      else
      {
        $this->SendAgainErrorPanel->setVisible(true);
      }
    }
	}

	public function onCancelBtn($sender, $param)	
	{		
		$this->Application->transfer('User:AdminPage');		
	}

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("User:InviteManagePage");
	}

}
?>
