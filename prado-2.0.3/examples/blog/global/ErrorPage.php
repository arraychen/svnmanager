<?php

class ErrorPage extends TPage
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		$handler=$this->Application->getErrorHandler();
		$this->ErrorCode->setText($handler->getErrorCode());
		$this->ErrorMessage->setText($handler->getErrorMessage());
	}
}

?>