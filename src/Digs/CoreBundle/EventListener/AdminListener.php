<?php
namespace Digs\CoreBundle\EventListener;

use Digs\CoreBundle\Controller\AdminController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContext;

class AdminListener
{
    private $security;

    public function __construct(SecurityContext $security)
    {
        $this->security = $security;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
		$controller = $event->getController();
		
		/*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
		if (!is_array($controller)) {
			return;
		}

        if ($controller[0] instanceof AdminController) {
			if (false === $this->security->isGranted('ROLE_ADMIN'))
			{
				throw new NotFoundHttpException();
			}
		}
	}
}