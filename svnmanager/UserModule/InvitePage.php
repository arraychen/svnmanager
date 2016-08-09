<?php

require_once('svnmanager/global/AdminPageBase.php');

/*
 * Created on 19-jan-2005
 * 
 * On this page the administrators can send invitation emails to new users 
 * 
 */
class InvitePage extends AdminPageBase
{

	/*
	 * Check if email address belongs to existing user
	 */
	public function isEmailTaken($sender, $param)
	{		
		$param->isValid=!$this->Module->isEmailTaken($this->Email->Text);		
	}
	
	/*
	 * Check if there is a pending ticket for this email address
	 */
	public function hasTicket($sender, $param)
	{
		$param->isValid=!$this->Module->hasTicket($this->Email->Text);
	}
	
	/*
	 * On confirmation, send the e-mail adres an invitation to make an account
	 * on this server.
	 */	
	public function onConfirmBtn($sender, $param)
	{
		if($this->IsValid)
		{			
			require("config.php");
			require("./svnmanager/library/class.phpmailer.php");

			$email = $this->Email->Text;
			$repos = (int)$this->Repos->Text;
			$rnd = rand(1,1000000);
			
			//Ticket string is MD5 hash of emailadres, nr of repos grants & random number
			$ticket = md5($email.$repos.$rnd);
			$servername =$_SERVER['SERVER_NAME'];
			$page = $_SERVER['PHP_SELF'];
			$port = $_SERVER['SERVER_PORT'];
			if ($port == '443')
    		{
     			$prot = "https";
	     		$port = "";
		    }
    		else	
			{
     			$prot = "http";
     			if ($port != '80')
			      $port = ":$port";
     			else
			      $port = "";
    		}
			
			$message = 
				"Your are invited to create an account on the $servername server. Please follow the link to activate your account:\n".
				"\n".
				"$prot://$servername$port$page?page=User:ActivatePage&ticket=$ticket\n".
				"\n".
				"Regards";
			
			$mail = new PHPMailer();
			$mail->From     = "svnmanager@$servername";
			$mail->FromName = "SVNManager";
			$mail->Host     = "$smtp_server";
			$mail->Mailer   = "smtp";
			$mail->Body = $message;
			$mail->AddAddress($email);
			$mail->Subject = "Account Invitation";

			//Email invitation and Store ticket into database 								
			if(!$mail->Send())
			{
				echo "Something went wrong sending email, please contact server administrator!";
				exit(-1);
			} else {

				$this->Module->createTicket($email, $ticket, $repos); //Put the ticket into the database

				//Show confirmation
				$this->InvitationPanel->setVisible(false);			
				$this->ConfirmationPanel->setVisible(true);
			}
		}
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
