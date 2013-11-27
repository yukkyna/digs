<?php

namespace Digs\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Digs\CoreBundle\DependencyInjection\Compiler\NavigationMenuCompilerPass;

class DigsCoreBundle extends Bundle
{
	public function build(ContainerBuilder $container) {
		parent::build($container);
		$container->addCompilerPass(new NavigationMenuCompilerPass());
	}
}
