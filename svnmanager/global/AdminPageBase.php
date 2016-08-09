<?php

//
// Base class for pages requiring administrative
// privileges to access.
//
// Brad Kimmel - 2007/09/15
//
class AdminPageBase extends TPage
{

	public function onInit($param)
	{

		parent::onInit($param);

		// Display an error message to the user if they are not
		// an administrator.
		if (!$this->User->isAdmin())
		{
			echo "Access Denied";
			exit(-1);
		}

	}

}

?>
