<?php
namespace Digs\CoreBundle\Extension\Twig;

use Digs\CoreBundle\Services\ProfileMenuManager;

class ProfileMenuManagerTwigExtension extends \Twig_Extension
{
    protected $service;

    public function __construct(ProfileMenuManager $service) {
        $this->service = $service;
    }

    public function getGlobals() {
        return array(
            'profilemenu' => $this->service,
        );
    }

    public function getName()
    {
        return 'ProfileMenuManagerTwigExtension';
    }
}
