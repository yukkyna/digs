<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

class AlertCompilerPass extends CompilerPassBase
{
	public function getManagerName()
	{
		return 'digs_core.alert_manager';
	}
	public function getItemTagName()
	{
		return 'digs.alert.item';
	}
}
