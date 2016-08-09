<?php
class HelloWorld extends TPage
{
	public function onLoad($param)
	{
		parent::onLoad($param);
	}

	public function clickMe($sender,$param)
	{
		//Get our service from the service manager
		$services = $this->Application->getServiceManager()->getServices('PhpBeans');
		
		//Get our specific object for the PhpBean service
		$helloWorldService = PhpBeanFinder::find($services, 'HelloWorld');
		
		//Call the method we want.
		$sender->Text = $helloWorldService->getHelloWorld();
	}
}
?>