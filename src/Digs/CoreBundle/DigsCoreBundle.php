<?php

namespace Digs\CoreBundle;

use Digs\CoreBundle\DependencyInjection\Compiler\AlertCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\MyMenuCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\NavigationMenuCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\ProfilePanelCompilerPass;
use Digs\CoreBundle\DependencyInjection\Compiler\TopPanelCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DigsCoreBundle extends Bundle
{
	public function build(ContainerBuilder $container) {
		parent::build($container);
		$container->addCompilerPass(new MyMenuCompilerPass());
		$container->addCompilerPass(new NavigationMenuCompilerPass());
		$container->addCompilerPass(new ProfilePanelCompilerPass());
		$container->addCompilerPass(new AlertCompilerPass());
		$container->addCompilerPass(new TopPanelCompilerPass());
	}
}
