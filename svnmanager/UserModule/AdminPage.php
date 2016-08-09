<?php
/*
 * Created on 19-jan-2005
 *
 */
class AdminPage extends TPage
{
	public function onInit($param)
	{
		parent::onInit($param);	
	}

	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->dataBind();		
	}
	
	public function onClickInviteBtn($sender,$param)
	{
		$this->Application->transfer("User:InvitePage");
	}
	
	public function onClickManageInviteBtn($sender,$param)
	{
		$this->Application->transfer("User:InviteManagePage");
	}
	
	public function onClickEditBtn($sender,$param)
	{
		$this->Application->transfer("User:EditSelectPage");
	}

	public function onClickRemoveBtn($sender,$param)
	{
		$this->Application->transfer("User:RemovePage");
	}
	
	public function onClickAddBtn($sender, $param)
	{
		$this->Application->transfer("User:AddPage");
	}
}
?>
