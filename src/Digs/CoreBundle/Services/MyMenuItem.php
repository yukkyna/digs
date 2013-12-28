<?php
namespace Digs\CoreBundle\Services;

class MyMenuItem
{
	protected $items;
	
	public function __construct($items)
	{
		$this->items = $items;
	}
	
	public function getItems()
	{
		return $this->items;
	}
}
