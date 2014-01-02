<?php
namespace Digs\CoreBundle\Services;

class AlertManager
{
	protected $items;
	
	public function __construct()
	{
		$this->items = array();
	}

	public function addItem(AlertItem $item)
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
