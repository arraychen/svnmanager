<?php

class NewPage extends TPage
{
	public function onClickPostBtn($sender,$param)
	{
		if($this->IsValid)
		{
			$blog=array(
				'title'=>$this->content->Title->Text,
				'content'=>$this->content->Content->Text
				);
			if($this->Module->createBlog($blog))
			{
				$this->Application->transfer();
			}
			else
			{
				// Unexpected case.
				// This exception will be captured by the framework
				// You can also explicitly specify a page for ErrorHandler.
				throw new Exception('Unable to create blog.');
			}
		}
	}
}

?>