<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\AlertManager;

class AlertManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(AlertManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'alert' => $this->service,
        );
    }

    public function getName()
    {
        return 'AlertManagerTwigExtension';
    }
}
