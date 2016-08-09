<?php

using('System.Data');

class AddEntryPage extends TPage
{
	public function addEntry($sender,$param)
	{
		if($this->IsValid)
		{
			$name=strtr(trim($this->Name->Text),array("'"=>"''"));
			$email=strtr(trim($this->Email->Text),array("'"=>"''"));
			$phone=strtr(trim($this->Phone->Text),array("'"=>"''"));
			$address=strtr(trim($this->Address->Text),array("'"=>"''"));
			$memo=strtr($this->Memo->Text,array("'"=>"''"));
			$db=new TAdodb;
			$db->DataSourceName=$this->Application->getUserParameter('DSN');
			$db->Execute("INSERT INTO tblEntry (name,email,phone,address,memo) VALUES ('$name','$email','$phone','$address','$memo')");
			$this->Application->transfer('HomePage',array(HomePage::FILTER=>$name{0}));
		}
	}
}

?>