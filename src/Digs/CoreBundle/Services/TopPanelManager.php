<?php
namespace Digs\CoreBundle\Services;

class TopPanelManager
{
	protected $items;
	
	public function __construct()
	{
		$this->items = array();
	}

	public function addItem(TopPanelItem $item)
	{
		foreach ($item->getItems() as $i)
		{
			$this->items[] = $i;
		}
	}
	
	public function getItems()
	{
		return $this->items;
	}
}
