<?php

namespace Digs\ImageConverterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DigsImageConverterBundle extends Bundle
{
	public function build(ContainerBuilder $container) {
		parent::build($container);
//		$container->register($id);
	}
}
