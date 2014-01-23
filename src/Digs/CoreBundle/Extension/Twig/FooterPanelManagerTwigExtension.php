<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\FooterPanelManager;

class FooterPanelManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(FooterPanelManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'footerpanel' => $this->service,
        );
    }

    public function getName()
    {
        return 'FooterPanelManagerTwigExtension';
    }
}
