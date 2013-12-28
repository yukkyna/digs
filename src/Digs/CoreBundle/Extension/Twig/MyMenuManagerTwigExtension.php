<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\MyMenuManager;

class MyMenuManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(MyMenuManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'mymenu' => $this->service,
        );
    }

    public function getName()
    {
        return 'MyMenuManagerTwigExtension';
    }
}
