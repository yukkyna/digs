<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

class NavigationMenuCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.navigation_manager';
	}
	public function getItemTagName()
	{
		return 'digs.navigation.item';
	}
}
