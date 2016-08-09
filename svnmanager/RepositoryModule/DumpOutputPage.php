<?php

class DumpOutputPage extends TPage
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

		$filename = $name.".dump";
	
	   
		if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
       		// IE Bug in download name workaround
       		error_log("ini_set");
       		ini_set( 'zlib.output_compression','Off' );
	   	} 
	
		header('Cache-Control:');
		header('Pragma:');
		header( "Content-Type: application/octet-stream");		
		header( "Content-Disposition: attachment; filename=\"$filename\"");				
		header( "Content-Transfer-Encoding: binary" );
		passthru( $svnadmin_cmd." dump ".$svn_repos_loc.DIRECTORY_SEPARATOR.$name );					
 		exit(0); 		
		//$this->Application->transfer('Repository:AdminPage');	

				
	}
	
}

?>
