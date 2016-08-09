<?php

class ViewAllPage extends TPage
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->content->BlogTable->setDataSource($this->Module->selectBlog());
		$this->content->BlogTable->dataBind();
	}

	public function onClickDeleteBtn($sender,$param)
	{
		$id=intval($param->parameter);
		if($this->Module->getUserParameter('AllowAllDelete')==='true' || $this->Module->canUpdateBlog($id))
		{
			$this->Module->deleteBlog($id);
			$this->Application->transfer();
		}
		else
			$this->User->onAuthorizationRequired($this);
	}
}

?>