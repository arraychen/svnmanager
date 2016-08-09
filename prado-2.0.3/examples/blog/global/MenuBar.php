<?php

class MenuBar extends TControl
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->dataBind();
	}

	public function onClickLogoutBtn($sender,$param)
	{
		$this->User->logout();
		$this->Application->transfer();
	}
}

?>