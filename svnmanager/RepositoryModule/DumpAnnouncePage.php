<?php

class DumpAnnouncePage extends TPage
{	
	
	function OnInit($param)
	{
		parent::onInit($param);
		
		include("config.php");
		
		$repositoryid = $_GET['RepositoryID'];
		
		$s_repositoryid = $this->Module->Database->qstr($repositoryid);
		
		$results = $this->Module->Database->Execute("SELECT * FROM repositories WHERE id=" . $s_repositoryid);
		$fields = $results->fields;
		$ownerid = $fields['ownerid'];
		$name = $fields['name'];
		
		if(!$this->User->isAdmin() && $this->User->getId()!=$ownerid)
		{
			echo "Not enough rights to change this repository!";
			exit(-1);
		}

		$servername =$_SERVER['SERVER_NAME'];
		$page = $_SERVER['PHP_SELF'];

		$port = $_SERVER['SERVER_PORT'];
		if($port=='443')
			$prot="https";
		else
		{
			$prot="http";
			if($port != '80')
			$servername .=":$port";
		}

		$url = $prot."://$servername$page?page=Repository:DumpOutputPage&RepositoryID=$repositoryid"; 
		
		$this->StartLink->setNavigateUrl("$url");
		header("Refresh: 3; URL=$url");		
				
	}
	
	public function OnGoBack($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}
	
}

?>
