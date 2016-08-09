<?php
/*
* Created on 17-Jan-2005
*
*/
using('System.Data');

class DataModule extends TModule
{

	public function onLoad($param)
	{
		parent::onLoad($param);
		//$this->Database->setDataSourceName($this->Application->getUserParameter('DSN'));			
		
		require("config.php");

		//Set UTF-8 encoding
		mb_internal_encoding('UTF-8');
		
		$this->Database->setDataSourceName($dsn);
		$this->Database->SetFetchMode("Associative");

		//Some SQL statements don't work with sqlite
		if(strncasecmp($dsn, "sqlite",6)==0)
			$sqlite=true;
		else
			$sqlite=false;
			
		// Get list of all tables in current database
	    $tables = $this->Database->MetaTables();

	    /*
	    ** Array of tables required.
	    **  To add a table, add the table name to this array, and
	    **  add the CREATE TABLE commands to the switch statement within
	    **  the foreach loop below
	    */
    	$requiredTables = array(
              "groupprivileges",
              "groups",
              "repositories",
              "repo_descriptions",
              "svnserve_pwd",
              "userprivileges",
              "users",
              "usersgroups",
              "usertickets"
            );

	       /*
    		** 2006-06-07 PDurden - Determine what tables are missing using
    		**    array_diff()
    		*/
    		$missingTables = array_diff($requiredTables, $tables);
    $numMissing = count($missingTables);
		if($numMissing > 0)
		{
      echo "<pre>\n";
      
      if ($numMissing == count($requiredTables))
      {
        echo "All tables are missing.\n";
      }
      else if ($numMissing < 2)
      {
        echo "There is 1 table missing.\n";
      }
      else
      {
        echo "There are $numMissing tables missing.\n";
      }
      
      echo "Creating requried tables...";
      
      /*
      ** Iterate though missing tables and create any needed tables.
      */
      foreach ($missingTables as $tableName)
      {
		$ac=""; //auto_increment statement doesn't work with sqlite
		if(!$sqlite)$ac=" auto_increment";

        //echo "  $tableName\n";
        switch ($tableName)
        {
          case "usertickets":
            $this->Database->Execute("
              CREATE TABLE usertickets (
                  email varchar(128) NOT NULL default '',
                  ticket varchar(32) NOT NULL default '',
                  repositorygrants tinyint(11) NOT NULL default '0'
              );
            ");
            break;
      
        case "usersgroups":
            //Usersgroups
            $this->Database->Execute("
              CREATE TABLE usersgroups (
                userid integer default '0',
                groupid integer default '0'
              );
            ");
            break;
            
          case "users":
			
			
            $this->Database->Execute("
              CREATE TABLE users (
                  id integer primary key".$ac.",
                  name varchar(32) NOT NULL default '',
                  password varchar(32) NOT NULL default '',
                  email varchar(128) NOT NULL default '',
                  admin integer NOT NULL default '0',
                  repositorygrants tinyint(11) NOT NULL default '0',
                  svnserve_password varchar(32) NOT NULL default ''
              );
            ");
            break;
          
          case "userprivileges":
            $this->Database->Execute("
              CREATE TABLE userprivileges (
                  id integer primary key".$ac.",
                  userid integer NOT NULL default '0',
                  repositoryid integer NOT NULL default '0',
                  access tinyint(4) NOT NULL default '0',
                  path varchar(255) NOT NULL default ''  					
              ); 
            ");
            break;
          
          case "svnserve_pwd":
            $this->Database->Execute("
              CREATE TABLE svnserve_pwd (
                ownerid integer primary key".$ac.",					
                password varchar(32) NOT NULL default ''
              );			
            ");
            break;
  
          case "groups":
            //Groups
            $this->Database->Execute("
              CREATE TABLE groups (
                id integer primary key".$ac.",
                name varchar(32) NOT NULL default '',
                adminid integer NOT NULL default '0'									
              );
            ");
            break;
        
          case "groupprivileges":
            $this->Database->Execute("
              CREATE TABLE groupprivileges (
                id INTEGER PRIMARY KEY".$ac.",
                groupid INTEGER NOT NULL DEFAULT '0',
                repositoryid INTEGER NOT NULL DEFAULT '0',
                access TINYINT(4) NOT NULL DEFAULT '0',
                path VARCHAR(255) NOT NULL DEFAULT ''
              );
            ");
            break;
              
          case "repositories":
            $this->Database->Execute("
              CREATE TABLE repositories (
                id integer  primary key".$ac.",
                name varchar(32) NOT NULL default '',
                ownerid integer NOT NULL default '0',
                description varchar(128) NOT NULL default ''
              );			
            ");
            break;
          
          case "repo_descriptions":
            $this->Database->Execute("
              CREATE TABLE repo_descriptions (
                id integer  primary key".$ac.",
                repo_id INTEGER NOT NULL DEFAULT '0',
                description varchar(128) NOT NULL default ''
              );			
            ");
            break;
            
          default:
            echo "  ERROR: I do not know how to create \"$tableName\" \n";
            break;
          
        }
      }

			echo "\n\nPlease reload page!\n</PRE>";
			exit(0);			
		}
	}
 
} 
?>