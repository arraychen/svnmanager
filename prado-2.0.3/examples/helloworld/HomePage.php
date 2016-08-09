<?php

class HomePage extends TPage
{
	function clickMe($sender,$param)
	{
		$sender->Text="Hello, world!";
	}
}

?>