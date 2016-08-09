<?php
using('System.Data');

class HomePage extends TPage
{
	function onInit($param)
	{
		parent::onInit($param);
		
		$this->Database->setDataSourceName($this->Application->getUserParameter('DSN'));
		
		if (!$this->isPostBack()) {
			
			$result=$this->Database->Execute("SELECT username FROM tblUser");
			$this->userDisplay->setDataTextField("username");
			$this->userDisplay->setDataValueField("username");
			$this->userDisplay->setDataSource($result);
			$this->userDisplay->dataBind();
			
		}
		
	}
	
	function changeDBList(TListControl $sender,$param)
	{
		// Get the selection from the DBList and add it to the check box list
		$items = $sender->getItems();
		$itemds = new ArrayObject();
		
		foreach ($items as $item) {
			if ($item->Selected) {
				$itemds[$item->Value] = $item->Text.'(#'.$item->Value.')';
			}
		}
		$this->checkList->setDataSource($itemds);
		$this->checkList->dataBind();
		
		$this->actionOptions->setVisible(true);
	}
	
	
	function selectUser($sender, $params)
	{
	}
	
	
	/**
	* When the number of posts changes, we want to update the listbox with
	* the related number of posts by that user.
	*/
	function selectNumber(TListControl $sender, $params)
	{
		$limit = $sender->getSelectedValue();
		
		$username = $this->userDisplay->getSelectedValue();
		
		// Now set up a database bound list using the blog's data.
		$result=$this->Database->Execute("SELECT * FROM tblBlog WHERE author='$username' ORDER BY wtime DESC LIMIT 0,$limit");
		
		// Set up the data selection fields.
		$this->DBList->setDataTextField("title");
		$this->DBList->setDataValueField("id");
		$this->DBList->setDataSource($result);
		$this->DBList->dataBind();
	}
	
	function changeChecks(TListControl $sender, $params)
	{
		//echo "Selected radio button ".$sender->getSelectedItem()->Text."<br/>";
	}
	
	public function performAction(TListControl $sender, $params)
	{
		echo "Selected {$sender->getSelectedValue()} <br/>";
		
		switch ($sender->getSelectedValue()) {
			case 'deleted': 
				$items = $this->checkList->getItems();
				foreach ($items as $item) {
					if ($item->Selected) {
						echo "Selected post id ".$item->Value."<br/>";
					}
				}
				break;
			default: break;
		}					
	}
}

?>