<?php
class AdminPage extends TPage
{
	public function onInit($param)
	{
		parent::onInit($param);
		$this->dataBind();										
	}
	public function onLoad($param)
	{
		parent::onLoad($param);		
	}
	public function onClickCreateBtn($sender, $param)
	{
		$this->Application->transfer("Repository:CreatePage");		
	}
	public function onClickRemoveBtn($sender, $param)
	{
		$this->Application->transfer("Repository:RemovePage");
	}
	public function onClickEditBtn($sender, $param)
	{
		$this->Application->transfer("Repository:EditSelectPage");
	}
	public function onClickUserPrivilegesBtn($sender, $param)
	{
		$this->Application->transfer("Repository:UserPrivilegesPage");
	}
	public function onClickGroupPrivilegesBtn($sender, $param)
	{
		$this->Application->transfer("Repository:GroupPrivilegesPage");
	}

	public function onClickDumpBtn($sender, $param)
	{
		$this->Application->transfer("Repository:DumpPage");
	}

	public function onClickLoadBtn($sender, $param)
	{
		$this->Application->transfer("Repository:LoadPage");
	}
	public function onClickRecoverBtn($sender, $param)
	{
		$this->Application->transfer("Repository:RecoverPage");		
	}	
	public function onClickImportBtn($sender, $param)
	{
		$this->Application->transfer("Repository:ImportSelectPage");		
	}	
}
?>