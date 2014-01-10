<?php
namespace Digs\CoreBundle\Services;

class ProfileMenuItem
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
