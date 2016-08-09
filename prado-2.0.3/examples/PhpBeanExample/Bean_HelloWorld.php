<?php

/**
 * HelloWorld.php This is a test PhpBeans for prado.
 */
class Bean_HelloWorld extends PHP_Bean
{
	//Construct our Bean!
	public function Bean_HelloWorld(&$server)
	{
		$this->init($server);
		$this->namespace = 'HelloWorld';
		$this->addMethods(__FILE__);
	}

	/**
     * Gets the hello world string.
     * @access public
     * @return string
     */
	public function getHelloWorld()
	{
		return 'Hello World';
	}
}
?>