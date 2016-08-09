<?php

require_once('svnmanager/global/AdminPageBase.php');

class AdminPage extends AdminPageBase
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
		$this->Application->transfer("Group:CreatePage");		
	}

	public function onClickRemoveBtn($sender, $param)
	{
		$this->Application->transfer("Group:RemovePage");
	}
	
	public function onClickEditBtn($sender, $param)
	{
		$this->Application->transfer("Group:EditSelectPage");
	}

	public function onClickExportBtn($sender, $param)
	{
		$this->Application->transfer("Group:ExportPage");
	}
}
?>
