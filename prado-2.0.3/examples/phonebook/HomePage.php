<?php

using('System.Data');

class HomePage extends TPage
{
	const FILTER='filter';
	private $filter='';
	private $db;

	public function onInit($param)
	{
		parent::onInit($param);
		$this->db=new TAdodb;
		$this->db->DataSourceName=$this->Application->getUserParameter('DSN');
	}

	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->filter=isset($_GET[self::FILTER])?$_GET[self::FILTER]:'';
		if(strlen($this->filter)>0)
			$this->filter=strtoupper($this->filter{0});
		$this->ShowAll->setEnabled($this->filter>='A' && $this->filter<='Z');
		$filters=$this->Filters;
		for($f=65;$f<=90;++$f)
		{
			$char=chr($f);
			$link=$this->createComponent('THyperLink',$char);
			$link->Text=$char;
			$link->Enabled=($this->filter!==$char);
			$link->NavigateUrl=$this->Request->constructUrl($this->ID,array(self::FILTER=>$char));
			$filters->addBody(' '); // separating space
			$filters->addBody($link);
		}
		if(strlen($this->filter))
			$rs=$this->db->Execute("SELECT * FROM tblEntry WHERE upper(name) like '{$this->filter}%'");
		else
			$rs=$this->db->Execute('SELECT * FROM tblEntry');
		$this->EntryTable->setDataSource($rs);
		$this->dataBind();
	}

	public function onEntryAction($sender,$param)
	{
		if($param->name==='delete')
		{
			if(!$this->User->IsAuthenticated())
				$this->User->OnAuthenticationRequired($this->ID);
			$id=$param->parameter;
			$this->db->Execute("DELETE FROM tblEntry WHERE id=$id");
			$this->Application->transfer('HomePage',array(HomePage::FILTER=>$this->filter));
		}
	}

	public function onLogout($sender,$param)
	{
		$this->User->logout();
		$this->Application->transfer($this->ID);
	}
}

?>