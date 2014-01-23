<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

class FooterPanelCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.footerpanel_manager';
	}
	public function getItemTagName()
	{
		return 'digs.footerpanel.item';
	}
}
