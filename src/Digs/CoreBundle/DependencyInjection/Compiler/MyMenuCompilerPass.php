<?php
namespace Digs\CoreBundle\DependencyInjection\Compiler;

class MyMenuCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.mymenu_manager';
	}
	public function getItemTagName()
	{
		return 'digs.mymenu.item';
	}
}
