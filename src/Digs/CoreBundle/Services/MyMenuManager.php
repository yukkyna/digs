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
			if (!isset($this->items[$i["section"]]))
			{
				$this->items[$i["section"]] = array();
			}
			$this->items[$i["section"]][] = $i;
		}

		$keys = array_keys($this->items);
		foreach ($keys as $key)
		{
			usort($this->items[$key], function($a, $b)
				{
					$ia = isset($a["weight"]);
					$ib = isset($b["weight"]);
					if (!$ia && !$ib)
					{
						return 0;
					}
					if (!$ia && $ib)
					{
						return -1;
					}
					if ($ia && !$ib)
					{
						return 1;
					}

					// usortの比較関数の戻り値は整数になるため小数点が指定された場合を考慮して単純な引き算はしない
					if ($a["weight"] > $b["weight"])
					{
						return 1;
					}
					if ($a["weight"] < $b["weight"])
					{
						return -1;
					}
					return 0;
				});
		}
	}

	public function getItems()
	{
		return $this->items;
	}
}
