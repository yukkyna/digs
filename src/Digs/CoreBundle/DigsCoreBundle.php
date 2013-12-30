<?php

namespace Digs\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Digs\CoreBundle\DependencyInjection\Compiler\MyMenuCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\NavigationMenuCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\ProfilePanelCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\TopPanelCompilerPass;

class DigsCoreBundle extends Bundle
{
	public function build(ContainerBuilder $container) {
		parent::build($container);
		$container->addCompilerPass(new MyMenuCompilerPass());
		$container->addCompilerPass(new NavigationMenuCompilerPass());
		$container->addCompilerPass(new ProfilePanelCompilerPass());
		$container->addCompilerPass(new TopPanelCompilerPass());
	}
}
