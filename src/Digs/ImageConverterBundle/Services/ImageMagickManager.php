<?php
namespace Digs\ImageConverterBundle\Services;

class ImageMagickManager
{
	private $im;
	
	public function __construct($path)
	{
		$this->im = $path;
	}
	public function convert($cmd)
	{
		$ret = array();
		exec('"' . $this->im . '" ' . $cmd . ' 2>&1', $ret);
		return count($ret);
	}
}
