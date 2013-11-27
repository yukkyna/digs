<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\NavigationMenuManager;

class NavigationMenuManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(NavigationMenuManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'navigation' => $this->service,
        );
    }

    public function getName()
    {
        return 'NavigationMenuManagerTwigExtension';
    }
}
