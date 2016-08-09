<?php

class EditPage extends TPage
{
	public function onAuthorize($user)
	{
		return $this->Module->canUpdateBlog($this->getBlogID());
	}

	public function onLoad($param)
	{
		parent::onLoad($param);
		if(!$this->IsPostBack)
		{
			$blog=$this->Module->selectBlog($this->getBlogID());
			if(empty($blog))
			{
				// Unexpected case.
				// This exception will be captured by the framework
				// You can also explicitly specify a page for ErrorHandler.
				throw new Exception('Unable to load blog data.');
			}
			$this->content->Title->setText($blog['title']);
			$this->content->Content->setText($blog['content']);
		}
	}

	public function onClickUpdateBtn($sender,$param)
	{
		if($this->IsValid)
		{
			$blog=array(
				'title'=>$this->content->Title->Text,
				'content'=>$this->content->Content->Text
				);
			if($this->Module->updateBlog($this->getBlogID(),$blog))
				$this->Application->transfer();
			else
			{
				// Unexpected case.
				// This exception will be captured by the framework
				// You can also explicitly specify a page for ErrorHandler.
				throw new Exception('Unable to update blog.');
			}
		}
	}

	protected function getBlogID()
	{
		$id=$this->Request->getParameter('id');
		return is_null($id)?-1:intval($id);
	}
}

?>