<?php
/*
** This module allows an admin to easily add existing repositories to 
** SVNManager
*/

class ImportSelectPage extends TPage
{
	
	public function onLoad($param)
	{
		parent::onLoad($param);

		if(!$this->User->IsAdmin())
    {
			return;
    }
    
		require "config.php";

    $data = array();
    /*
    ** 2006-04-06 PDurden - Walk through the files in the repository
    **   location. If the file is a directory, check to see if the repository
    **   name exists. If the name does not exist, then add it to the data 
    **   array
    */
    
    if ($handle = opendir($svn_repos_loc)) 
    {
      while (false !== ($dir = readdir($handle)))
      {
        if ($dir != "." && $dir != "..")
        {
          $file = $svn_repos_loc.DIRECTORY_SEPARATOR.$dir;
          if (is_dir($file))
          {
            if (!$this->Module->repositoryExists($dir))
            {
              $data[] = array('repositoryname' => $dir);
            }
          }
        }
      }
      closedir($handle);
    }
    
    $this->RepositoryTable->setDataSource($data);
    $this->RepositoryTable->dataBind();
	}
	
	public function onSelectRepository($sender, $param)
	{
    $name = $param->parameter;
    $userID = $this->User->getId(); 
		$s_name = $this->Module->Database->qstr($name);
		$s_userID = $this->Module->Database->qstr($userID);
    
    //Add repository into database
    $result = $this->Module->Database->Execute("INSERT INTO repositories (id, name, ownerid) VALUES (null, $s_name, $s_userID)");
    
    //Rebuild accessfile
    $this->Module->rebuildAccessFile();
		
    $this->ImportPanel->setVisible(false);
    $this->RepoImportedPanel->setVisible(true);			
	}

	public function onCancelBtn($sender, $param)
	{		
		$this->Application->transfer('Repository:AdminPage');
	}	

	public function onGoBack($sender, $param)
	{
		$this->Application->transfer("Repository:ImportSelectPage");
	}	
  
}
?>
