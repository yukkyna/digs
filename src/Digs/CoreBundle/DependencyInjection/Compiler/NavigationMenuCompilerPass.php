<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class NavigationMenuCompilerPass implements CompilerPassInterface
{
	protected function getManagerName()
	{
		return 'digs_core.navigation_manager';
	}
	protected function getItemTagName()
	{
		return 'digs.navigation.item';
	}

	public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->getManagerName())) {
            return;
        }
        $definition = $container->getDefinition($this->getManagerName());

        $taggedServices = $container->findTaggedServiceIds($this->getItemTagName());
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addItem',
                array(new Reference($id))
            );
        }
    }
}
