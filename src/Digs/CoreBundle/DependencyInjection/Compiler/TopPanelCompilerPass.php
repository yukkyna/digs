<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

class TopPanelCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.toppanel_manager';
	}
	public function getItemTagName()
	{
		return 'digs.toppanel.item';
	}
}
