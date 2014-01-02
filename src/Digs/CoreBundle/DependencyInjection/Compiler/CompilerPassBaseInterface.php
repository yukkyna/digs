<?php

namespace Digs\CoreBundle\DependencyInjection\Compiler;

interface CompilerPassBaseInterface
{
	public function getManagerName();
	public function getItemTagName();
}
