<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\TopPanelManager;

class TopPanelManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(TopPanelManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'toppanel' => $this->service,
        );
    }

    public function getName()
    {
        return 'TopPanelManagerTwigExtension';
    }
}
