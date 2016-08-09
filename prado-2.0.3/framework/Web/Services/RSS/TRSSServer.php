<?php

require_once(dirname(__FILE__).'/RSSBuilder.php');

class TRSSServer
{
	protected $handler;
	protected $builder;
	protected $version;
	
	public function __construct(IRSSEventHandler $handler, $version)
	{
		$this->handler = $handler;
		$this->setVersion($version);
	}	
			
	public function getVersion()
	{
		return $this->version;
	}
	
	public function setVersion($version)
	{
		$this->version = $version;
	}
	
	public function execute()
	{
		$this->buildInfo();
		$this->buildItemList();
		$this->buildDublicCore();
		$this->buildSyndication();
		$this->outputRSS();
	}
	
	protected function buildInfo()
	{
		$info = $this->handler->getRSSInfo();
		if($info instanceof TRSSInfo)
		{
			$this->builder = new RSSBuilder
				($info->encoding, $info->about, $info->title,
				$info->description, $info->image_link, $info->category);		
		}
	}
	
	protected function buildItemList()
	{
		$items = $this->handler->getRSSItemList();
		foreach($items as $item)
		{
			if($item instanceof TRSSItem)
			{
				$this->builder->addRSSItem(
					$item->about, $item->title, $item->link, 
					$item->description, $item->subject, $item->date, 
					$item->author, $item->comments, $item->image);
			}
		}
	}
	
	protected function buildDublicCore()
	{
		$dc = $this->handler->getRSSDublicCore();
		if($dc instanceof TRSSDublicCore)
		{
			$this->builder->addDCdata(
				$dc->publisher,	$dc->creator, $dc->date, $dc->language,	
				$dc->rights, $dc->coverage, $dc->contributor);			
		}
	}
	
	protected function buildSyndication()
	{
		$synd = $this->handler->getRSSSyndication();
		if($synd instanceof TRSSSyndication)
		{
			$this->builder->addSYdata($synd->period, $synd->frequency, $synd->base);
		}
	}
	
	protected function outputRSS()
	{
		$this->builder->outputRSS($this->version);
	}
}

?>