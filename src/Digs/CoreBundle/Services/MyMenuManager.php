<?php
namespace Digs\CoreBundle\Services;

class MyMenuManager
{
	protected $items;
	
	public function __construct()
	{
		$this->items = array();
	}

	public function addItem(MyMenuItem $item)
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
