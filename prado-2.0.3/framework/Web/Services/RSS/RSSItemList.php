<?php
require_once(dirname(__FILE__).'/RSSBase.php');
require_once(dirname(__FILE__).'/RSSObjectList.php');
require_once(dirname(__FILE__).'/RSSItemData.php');

class RSSItemList extends RSSObjectList 
{
	
	function __construct() 
	{
		parent::__construct(0,100);
	} // end constructor
	
	public function addRSSItem(RSSItemData $item) 
	{
		$this->addObject($item);
	} // end function
	
} // end class
?>