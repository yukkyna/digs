<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\ProfilePanelManager;

class ProfilePanelManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(ProfilePanelManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'profilepanel' => $this->service,
        );
    }

    public function getName()
    {
        return 'ProfilePanelManagerTwigExtension';
    }
}
