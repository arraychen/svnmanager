<?php

class ExportPage extends TPage
{
  protected function getAuthorTableData()
  {
  	$results = $this->Module->Database->Execute("SELECT * FROM users ORDER BY name");

		if($results)
		{
			$data = array();
			while(!$results->EOF)
			{
				$fields = $results->fields;
				$data[] = array(
					'username'     => $fields['name'],
					'emailaddress' => $fields['email']
				);
			
				$results->MoveNext();
								
			}			
			$results->Close();
      return $data;
    }
  }
  
  protected function getGroupListData()
  {
  	$groups = $this->Module->Database->Execute("SELECT * FROM groups ORDER BY name");

    // 2006-03-29 PDurden - a bug in mailer.py causes the to_addr mapping to
    //    not work with mixed case list names, so set the name to lower
		if($groups)
		{
      $data = array();
			while(!$groups->EOF)
			{
				$fields = $groups->fields;
				$data[] = array(
					'groupname' => strtolower($fields['name']),
					'groupid'   => $fields['id']
				);
        
				$groups->MoveNext();
			}
			$groups->Close();
      return $data;
    }
  }
  
	protected function getUserEmail($userid)
	{
		$s_userid = $this->Module->Database->qstr($userid);
		$user = $this->Module->Database->Execute("SELECT * FROM users WHERE id=".$s_userid);
		if ($user)
    {
			return $user->fields['email'];
    }
		else
    {
			return null;
    }
	}
  
  protected function getGroupMemebersData($groupid)
  {
  	$s_groupid = $this->Module->Database->qstr($groupid);
    $userid = $this->Module->Database->Execute("SELECT * FROM usersgroups WHERE groupid=".$s_groupid);
    
    if ($userid)
    {
      $data = array();
      while (!$userid->EOF)
      {
				$data[] = array(
					'emailaddress' => $this->getUserEmail($userid->fields['userid'])
				);
        
        $userid->MoveNext();
      }
      $userid->Close();
      
      return $data;
    }
  }

	public function onInit($param)
	{
    if (!$this->IsPostBack)
    {
      $groupList  = $this->getGroupListData();
      $authorList = $this->getAuthorTableData();
      parent::onLoad($param);
      
      $this->AuthorTable->setDataSource($authorList);
      $this->AuthorTable->dataBind();
      
      $this->GroupList->setDataSource($groupList);
      $this->GroupList->dataBind();
    }
	}

  public function setGroupMembers($sender, $param)
  {
    $groupID = ($param->item->Data['groupid']);
    $memberList = $this->getGroupMemebersData($groupID);
    $param->item->GroupMembers->setDataSource($memberList);
    $param->item->GroupMembers->dataBind();
  }
	
}
?>
