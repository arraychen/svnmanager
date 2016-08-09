<?php
class PollControl extends TControl 
{
	function recordVote($sender, $params)
	{
		$item = $this->pollOptions->getSelectedItem();
		if ($item) 
			$this->addBody("Selected ".$item->getText());
	}
}
?>