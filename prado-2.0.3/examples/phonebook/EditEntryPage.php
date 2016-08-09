<?php

using('System.Data');

class EditEntryPage extends TPage
{
	private $db;
	private $id=0;

	public function onInit($param)
	{
		parent::onInit($param);
		$this->db=new TAdodb;
		$this->db->DataSourceName=$this->Application->getUserParameter('DSN');
	}

	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->id=isset($_GET['id'])?intval($_GET['id']):0;
		if(!$this->IsPostBack)
		{
			$rs=$this->db->Execute("SELECT * FROM tblEntry WHERE id={$this->id}");
			if(!$rs->EOF)
			{
				$this->Name->setText($rs->fields['name']);
				$this->Email->setText($rs->fields['email']);
				$this->Phone->setText($rs->fields['phone']);
				$this->Address->setText($rs->fields['address']);
				$this->Memo->setText($rs->fields['memo']);
			}
		}
	}

	public function editEntry($sender,$param)
	{
		if($this->IsValid)
		{
			$name=strtr(trim($this->Name->Text),array("'"=>"''"));
			$email=strtr(trim($this->Email->Text),array("'"=>"''"));
			$phone=strtr(trim($this->Phone->Text),array("'"=>"''"));
			$address=strtr(trim($this->Address->Text),array("'"=>"''"));
			$memo=strtr($this->Memo->Text,array("'"=>"''"));
			$this->db->Execute("UPDATE tblEntry SET name='$name', email='$email', phone='$phone', address='$address', memo='$memo' WHERE id={$this->id}");
			$this->Application->transfer('HomePage',array(HomePage::FILTER=>$name{0}));
		}
	}
}

?>