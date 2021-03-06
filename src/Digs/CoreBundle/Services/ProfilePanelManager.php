<?php
namespace Digs\CoreBundle\Services;

class ProfilePanelManager
{
	protected $items;
	
	public function __construct()
	{
		$this->items = array();
	}

	public function addItem(ProfilePanelItem $item)
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
