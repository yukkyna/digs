<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

class ProfileMenuCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.profilemenu_manager';
	}
	public function getItemTagName()
	{
		return 'digs.profilemenu.item';
	}
}
