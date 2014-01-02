<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

class ProfilePanelCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.profilepanel_manager';
	}
	public function getItemTagName()
	{
		return 'digs.profilepanel.item';
	}
}
